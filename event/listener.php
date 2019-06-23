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

use paul999\tfa\helper\session_helper_interface;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/**
	 * @var session_helper_interface
	 */
	private $session_helper;

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
	 * @var template
	 */
	private $template;

	/**
	 * @var config
	 */
	private $config;

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
	 * @access   public
	 *
	 * @param session_helper_interface          $session_helper
	 * @param user                              $user
	 * @param request_interface                 $request
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\template\template          $template
	 * @param \phpbb\config\config              $config
	 * @param string                            $php_ext
	 * @param string                            $root_path
	 */
	public function __construct(session_helper_interface $session_helper, user $user, request_interface $request, driver_interface $db, template $template, config $config, $php_ext, $root_path)
	{
		$this->session_helper		= $session_helper;
		$this->user					= $user;
		$this->request				= $request;
		$this->config				= $config;
		$this->db					= $db;
		$this->template				= $template;
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
	public static function getSubscribedEvents()
	{
		return array(
			'core.auth_login_session_create_before'		=> 'auth_login_session_create_before',
			'core.user_setup_after'						=> 'user_setup_after',
			'core.permissions'			        		=> 'add_permission',
		);
	}

	/**
	 * @param \phpbb\event\data $event
	 */
	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['a_tfa'] = array('lang' => 'ACL_A_TFA', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}

	/**
	 * @param \phpbb\event\data $event
	 */
	public function user_setup_after($event)
	{
		// We skip this when tfa is disabled or we are at a page related to login (This includes logout :))
		if ($this->config['tfa_mode'] == session_helper_interface::MODE_DISABLED || defined('IN_LOGIN'))
		{
			return;
		}
		if (strpos($this->user->page['page_name'], 'app' . $this->php_ext) !== false && strrpos($this->user->page['page_name'], 'paul999/tfa') !== false) {
            @define('SKIP_CHECK_DISABLED', true);
        }

		if ($this->user->data['is_bot'] == false && $this->user->data['user_id'] != ANONYMOUS && $this->session_helper->isTfaRequired($this->user->data['user_id'], false, $this->user->data) && !$this->session_helper->isTfaRegistered($this->user->data['user_id']))
		{
		    @define('SKIP_CHECK_DISABLED', true);
			$sql = 'SELECT module_id FROM ' . MODULES_TABLE . " WHERE module_langname = 'UCP_TFA' OR module_langname = 'UCP_TFA_MANAGE'";
			$result = $this->db->sql_query($sql, 3600);
			$allowed_i = array();

			while ($row = $this->db->sql_fetchrow($result))
			{
				$allowed_i[] = $row['module_id'];
			}
			$this->db->sql_freeresult($result);
			$ucp_mode = '-paul999-tfa-ucp-tfa_module';
			$allowed_i[] = $ucp_mode;

			if ($this->user->page['page_name'] === 'ucp.' . $this->php_ext && in_array($this->request->variable('i', ''), $allowed_i))
			{
				return; // We are at our UCP page, so skip any other checks. This page is always available
			}
			$this->user->add_lang_ext('paul999/tfa', 'common');
			$url = append_sid("{$this->root_path}ucp.{$this->php_ext}", "i={$ucp_mode}");
			$msg_text = $this->user->lang('TFA_REQUIRED_KEY_MISSING', '<a href="' . $url . '">', '</a>');
			$msg_title =  $this->user->lang['INFORMATION'];

			page_header($msg_title);

			$this->template->set_filenames(array(
					'body' => 'message_body.html')
			);

			$this->template->assign_vars(array(
					'MESSAGE_TITLE'		=> $msg_title,
					'MESSAGE_TEXT'		=> $msg_text,
					'S_USER_WARNING'	=> true,
					'S_USER_NOTICE'		=> false,
			));

			// We do not want the cron script to be called on error messages
			define('IN_CRON', true);

			page_footer();

			exit_handler();
		}
	}

	/**
	 * @param \phpbb\event\data $event
	 *
	 * @return \phpbb\event\data $event|null
	 * @throw http_exception
	 */
	public function auth_login_session_create_before($event)
	{
		if ($this->config['tfa_mode'] == session_helper_interface::MODE_DISABLED)
		{
			return $event;
		}
		if (isset($event['login'], $event['login']['status']) && $event['login']['status'] == LOGIN_SUCCESS)
		{
			// We have a LOGIN_SUCCESS result.
			if ($this->session_helper->isTfaRequired($event['login']['user_row']['user_id'], $event['admin'], $event['user_row']))
			{
				if (!$this->session_helper->isTfaRegistered($event['login']['user_row']['user_id']))
				{
					// While 2FA is enabled, the user has no methods added.
					// We simply return and continue the login procedure (The normal way :)),
					// and will disable all pages until he has added a 2FA key.
					return $event;
				}
				else
				{
					$this->session_helper->generate_page($event['login']['user_row']['user_id'], $event['admin'], $event['view_online'], !$this->request->is_set_post('viewonline'), $this->request->variable('redirect', ''));
				}
			}
		}
		return null;
	}
}
