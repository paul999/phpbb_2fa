<?php
/**
*
* 2FA extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Paul Sohier
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace paul999\tfa\helper;
use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;

/**
 * helper method which is used to detect if a user needs to use 2FA
 */
class sessionHelper implements sessionHelperInterface
{

	/**
	 * @var driver_interface
	 */
	private $db;

	/**
	 * @var config
	 */
	private $config;

	/**
	 * @var string
	 */
	private $registration_table;

	/**
	 * @var string
	 */
	private $user_table;

	/**
	 * @var array
	 */
	private $user_array = array();

	/**
	 * Constructor
	 *
	 * @access public
	 * @param driver_interface $db
	 * @param config $config
	 * @param string $registration_table
	 * @param string $user_table
	 */
	public function __construct(driver_interface $db, config $config, $registration_table, $user_table)
	{
		$this->db					= $db;
		$this->config				= $config;
		$this->registration_table	= $registration_table;
		$this->user_table			= $user_table;
	}

	/**
	 * @param int $user_id
	 * @param bool $admin
	 * @param array $userdata
	 * @param bool $try
	 * @return bool
	 */
	public function isTfaRequired($user_id, $admin = false, $userdata = array(), $try = false)
	{
		switch ($this->config['tfa_mode'])
		{
			case sessionHelperInterface::MODE_DISABLED:
				return false;
			case sessionHelperInterface::MODE_NOT_REQUIRED:
				return $this->isTfaRegistered($user_id);
			case sessionHelperInterface::MODE_REQUIRED_FOR_ACP_LOGIN:
				return $this->do_permission_check($user_id, $userdata, 'a_', $admin, $try);
			case sessionHelperInterface::MODE_REQUIRED_FOR_ADMIN:
				return $this->do_permission_check($user_id, $userdata, 'a_', true, $try);
			case sessionHelperInterface::MODE_REQUIRED_FOR_MODERATOR:
				return $this->do_permission_check($user_id, $userdata, array('m_', 'a_'), $admin, true);
			case sessionHelperInterface::MODE_REQUIRED:
				return true;
			default:
				return false;
		}
	}

	/**
	 * Check if the user has two factor authentication added to his account.
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public function isTfaRegistered($user_id)
	{
		if (isset($this->user_array[$user_id]))
		{
			return $this->user_array[$user_id];
		}
		$sql = 'SELECT COUNT(registration_id) as reg_id FROM ' . $this->registration_table . ' WHERE user_id = ' . (int)$user_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$this->user_array[$user_id] = $row && $row['reg_id'] > 0;
		return $this->user_array[$user_id];
	}

	/**
	 * Return the userdata for a specific user.
	 *
	 * @param int $user_id
	 * @param array $userdata
	 * @return array
	 */
	private function user_data($user_id, $userdata = array())
	{
		if (empty($userdata))
		{
			$sql = 'SELECT * FROM ' . $this->user_table . 'WHERE user_id = ' . (int) $user_id;
			$result = $this->db->sql_query($sql);
			$userdata = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);
		}
		return $userdata;
	}

	/**
	 * @param int $user_id
	 * @param array $userdata
	 * @param string|array $permission
	 * @param bool $admin
	 * @param bool $try
	 * @return bool
	 */
	private function do_permission_check($user_id, $userdata, $permission, $admin, $try)
	{
		if ($this->isTfaRegistered($user_id))
		{
			return true;
		}
		$userdata = $this->user_data($user_id, $userdata);
		$at = new auth();
		$at->acl($userdata);

		if (!is_array($permission))
		{
			$permission = array($permission);
		}
		foreach ($permission as $perm)
		{
			if ($at->acl_get($perm) && ($admin || $try))
			{
				return true;
			}
		}
		return false;
	}
}
