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

	private $config;

	private $registration_table;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param driver_interface $db
	 * @param config $config
	 * @param $registration_table
	 */
	public function __construct(driver_interface $db, config $config, $registration_table)
	{
		$this->db					= $db;
		$this->config				= $config;
		$this->registration_table	= $registration_table;
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
		// TODO: Implement isTfaRequired() method.
		return true;
	}

	/**
	 * Check if the user has two factor authentication added to his account.
	 *
	 * @param array $user_id
	 * @return bool
	 */
	public function isTfaRegistered($user_id)
	{
		$sql = 'SELECT COUNT(registration_id) as reg_id FROM ' . $this->registration_table . ' WHERE user_id = ' . (int)$user_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($row && $row['reg_id'] > 0)
		{
			return true;
		}
		return false;
	}
}
