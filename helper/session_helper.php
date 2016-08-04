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

use paul999\tfa\exceptions\module_exception;
use paul999\tfa\modules\module_interface;
use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\di\service_collection;
use phpbb\user;

/**
 * helper method which is used to detect if a user needs to use 2FA
 */
class session_helper implements session_helper_interface
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
	 * @var user
	 */
	private $user;

	/**
	 * @var array
	 */
	private $modules = array();

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
	 * @param user $user
	 * @param service_collection $modules
	 * @param string $registration_table
	 * @param string $user_table
	 */
	public function __construct(driver_interface $db, config $config, user $user, service_collection $modules, $registration_table, $user_table)
	{
		$this->db					= $db;
		$this->user					= $user;
		$this->config				= $config;
		$this->registration_table	= $registration_table;
		$this->user_table			= $user_table;

		$this->validateModules($modules);
	}

	/**
	 * Register the tagged modules if they are enabled.
	 * @param service_collection $modules
	 */
	private function validateModules(service_collection $modules)
	{
		/**
		 * @var module_interface $module
		 */
		foreach ($modules as $module)
		{
			if ($module instanceof module_interface)
			{
				// Only add them if they are actually a module_interface.
				$priority = $module->get_priority();
				if (isset($this->modules[$module->get_priority()]))
				{
					throw new module_exception($this->user->lang('TFA_DOUBLE_PRIORITY', $priority, get_class($module), get_class($this->modules[$priority])));
				}
				if ($module->is_enabled())
				{
					$this->modules[$priority] = $module;
				}
			}
		}
	}

	/**
	 * @param $requested_module
	 * @return null|module_interface
	 */
	public function findModule($requested_module)
	{
		/**
		 * @var module_interface $module
		 */
		foreach ($this->getModules() as $module)
		{
			if (get_class($module) == $requested_module)
			{
				return $module;
			}
		}
		return null;
	}

	/**
	 * @return array
	 */
	public function getModules()
	{
		return $this->modules;
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
		if (sizeof($this->modules) == 0)
		{
			return false;
		}
		switch ($this->config['tfa_mode'])
		{
			case session_helper_interface::MODE_DISABLED:
				return false;
			case session_helper_interface::MODE_NOT_REQUIRED:
				return $this->isTfaRegistered($user_id);
			case session_helper_interface::MODE_REQUIRED_FOR_ACP_LOGIN:
				return $this->do_permission_check($user_id, $userdata, 'a_', $admin, $try);
			case session_helper_interface::MODE_REQUIRED_FOR_ADMIN:
				return $this->do_permission_check($user_id, $userdata, 'a_', true, $try);
			case session_helper_interface::MODE_REQUIRED_FOR_MODERATOR:
				return $this->do_permission_check($user_id, $userdata, array('m_', 'a_'), $admin, true);
			case session_helper_interface::MODE_REQUIRED:
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

        $this->user_array[$user_id] = false; // Preset to false.

		/**
		 * @var int $priority
		 * @var module_interface $module
		 */
		foreach ($this->modules as $priority => $module)
		{
			$this->user_array[$user_id] = $this->user_array[$user_id] || $module->is_usable($user_id);
		}
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
