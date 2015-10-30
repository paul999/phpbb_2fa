<?php
/**
*
* 2FA extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Paul Sohier
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace paul999\tfa\event;

use paul999\tfa\helper\sessionHelperInterface;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/**
	 * @var sessionHelperInterface
	 */
	private $helper;

	/**]
	 * @var helper
	 */
	private $controller_helper;

	/**
	 * @var user
	 */
	private $user;

	/**
	 * @var request_interface
	 */
	private $request;

	/**
	 * @var driver_interface
	 */
	private $db;

	/**
	 * @var string
	 */
	private $php_ext;

	/**
	 * @var string
	 */
	private $root_path;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param sessionHelperInterface $helper
	 * @param helper $controller_helper
	 * @param user $user
	 * @param request_interface $request
	 * @param string $php_ext
	 * @param string $root_path
	 */
	public function __construct(sessionHelperInterface $helper, helper $controller_helper, user $user, request_interface $request, driver_interface $db, $php_ext, $root_path)
	{
		$this->helper				= $helper;
		$this->controller_helper 	= $controller_helper;
		$this->user					= $user;
		$this->request				= $request;
		$this->db					= $db;
		$this->php_ext				= $php_ext;
		$this->root_path			= $root_path;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 * @static
	 * @access public
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.auth_login_session_create_before'		=> 'auth_login_session_create_before',
			'core.user_setup_after'						=> 'user_setup_after',
			'core.permissions'			        		=> 'add_permission',
		);
	}

	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['a_tfa'] = array('lang' => 'ACL_A_TFA', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}

	public function user_setup_after($event)
	{
		if ($this->user->data['is_bot'] == false && $this->user->data['user_id'] != ANONYMOUS)
		{
			$ucp_mode = '';
			$sql = 'SELECT module_id FROM ' . MODULES_TABLE . ' WHERE module_langname = \'UCP_TFA\' OR module_langname = \'UCP_TFA_MANAGE\'';
			$result = $this->db->sql_query($sql);
			$allowed_i = array();

			while ($row = $this->db->sql_fetchrow($result))
			{
				$allowed_i[] = $row['module_id'];
			}
			$this->db->sql_freeresult($result);
			$ucp_mode = "-paul999-tfa-ucp-tfa_module";
			$allowed_i[] = $ucp_mode;

			if ($this->user->page['page_name'] == 'ucp.' . $this->php_ext && in_array($this->request->variable('i', ''), $allowed_i))
			{
				return; // We are at our UCP page, so skip any other checks. This page is always available
			}
			if ($this->helper->isTfaRequired($this->user->data['user_id']) && !$this->helper->isTfaRegistered($this->user->data['user_id']))
			{
				$this->user->add_lang_ext('paul999/tfa', 'common');
				$url = append_sid("{$this->root_path}ucp.{$this->php_ext}", "i={$ucp_mode}");
				trigger_error($this->user->lang('TFA_REQUIRED_KEY_MISSING', '<a href="' . $url . '">', '</a>'), E_USER_WARNING);
			}
		}
	}

	/**
	 * @param object $event
	 */
	public function auth_login_session_create_before($event)
	{
		if (isset($event['login']) && isset($event['login']['status']) && $event['login']['status'] == LOGIN_SUCCESS)
		{
			// We have a LOGIN_SUCESS result.
			if ($this->helper->isTfaRequired($event['login']['user_row']['user_id'], $event['admin'], $event['user_row']))
			{
				if (!$this->helper->isTfaRegistered($event['login']['user_row']['user_id']))
				{
					// While 2FA is enabled, the user has no methods added.
					// We simply return and continue the login procedure (The normal way :)),
					// and will disable all pages untill he has added a 2FA key.
					return;
				}
				else
				{
					redirect($this->controller_helper->route('paul999_tfa_read_controller', array(
						'user_id'		=> (int)$event['login']['user_row']['user_id'],
						'admin'			=> (int)$event['admin'],
						'auto_login'	=> (int)$event['auto_login'],
						'viewonline'	=> 0,
					)));
				}
			}
		}
	}
}
