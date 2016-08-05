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
use paul999\tfa\modules\module_interface;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/**
	 * @var session_helper_interface
	 */
	private $session_helper;

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
	 * @var \phpbb\template\template
	 */
	private $template;

	/**
	 * Constructor
	 *
	 * @access public
	 *
	 * @param session_helper_interface          $session_helper
	 * @param helper                            $controller_helper
	 * @param user                              $user
	 * @param request_interface                 $request
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\config\config              $config
	 * @param \phpbb\template\template          $template
	 * @param string                            $php_ext
	 * @param string                            $root_path
	 */
	public function __construct(session_helper_interface $session_helper, helper $controller_helper, user $user, request_interface $request, driver_interface $db, config $config, template $template, $php_ext, $root_path)
	{
		$this->session_helper		= $session_helper;
		$this->controller_helper 	= $controller_helper;
		$this->user					= $user;
		$this->request				= $request;
		$this->config				= $config;
		$this->db					= $db;
		$this->php_ext				= $php_ext;
		$this->root_path			= $root_path;
		$this->template				= $template;
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

	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['a_tfa'] = array('lang' => 'ACL_A_TFA', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}

	public function user_setup_after($event)
	{
		if ($this->config['tfa_mode'] == session_helper_interface::MODE_DISABLED)
		{
			return;
		}
		if (defined('IN_LOGIN'))
		{
			// We skip this when we are at a page related to login (This includes logout :))
			return;
		}
		if ($this->user->data['is_bot'] == false && $this->user->data['user_id'] != ANONYMOUS && $this->session_helper->isTfaRequired($this->user->data['user_id'], false, $this->user->data) && !$this->session_helper->isTfaRegistered($this->user->data['user_id']))
		{
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
			$this->user->add_lang_ext('paul999/tfa', 'common');
			$url = append_sid("{$this->root_path}ucp.{$this->php_ext}", "i={$ucp_mode}");
			trigger_error($this->user->lang('TFA_REQUIRED_KEY_MISSING', '<a href="' . $url . '">', '</a>'), E_USER_WARNING);

		}
	}

	/**
	 * @param object $event
	 *
	 * @return object
	 * @throw BadRequestHttpException
	 */
	public function auth_login_session_create_before($event)
	{
		if ($this->config['tfa_mode'] == session_helper_interface::MODE_DISABLED)
		{
			return $event;
		}
		if (isset($event['login']) && isset($event['login']['status']) && $event['login']['status'] == LOGIN_SUCCESS)
		{
			// We have a LOGIN_SUCESS result.
			if ($this->session_helper->isTfaRequired($event['login']['user_row']['user_id'], $event['admin'], $event['user_row']))
			{
				if (!$this->session_helper->isTfaRegistered($event['login']['user_row']['user_id']))
				{
					// While 2FA is enabled, the user has no methods added.
					// We simply return and continue the login procedure (The normal way :)),
					// and will disable all pages untill he has added a 2FA key.
					return $event;
				}
				else
				{
					$this->user->add_lang_ext('paul999/tfa', 'common');
					$user_id = $event['login']['user_row']['user_id'];
					$modules = $this->session_helper->getModules();
					$module = null;

					/**
					 * @var module_interface $module
					 */
					if (!empty($class) && $class != '_')
					{
						$module = $this->session_helper->findModule($class);
					}
					else
					{
						/**
						 * @var module_interface $row
						 */
						foreach ($modules as $row)
						{
							if ($row->is_usable($user_id))
							{
								$this->template->assign_block_vars('tfa_options', array_merge(array(
									'ID'	=> $row->get_name(),
									'U_SUBMIT_AUTH'	=> $this->controller_helper->route('paul999_tfa_read_controller_submit', array(
										'user_id'		=> (int) $user_id,
										'admin'			=> (int) $event['admin'],
										'auto_login'	=> (int) $event['auto_login'],
										'viewonline'	=> (int) !$this->request->is_set_post('viewonline'),
										'class'			=> $row->get_name(),
									)),
								), $row->login_start($user_id)));
							}
						}
					}

					add_form_key('tfa_login_page');

					$random = sha1(random_bytes(32));
					$this->user->set_cookie('rm', $random, 600);

					if (!empty($this->user->data['tfa_random']))
					{
						throw new BadRequestHttpException($this->user->lang('TFA_SOMETHING_WENT_WRONG'));
					}

					$sql_ary = array(
						'tfa_random' 	=> $random,
						'tfa_uid'		=> $user_id,
					);
					$sql = 'UPDATE ' . SESSIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE
								session_id = \'' . $this->db->sql_escape($this->user->data['session_id']) . '\' AND
								session_user_id = ' . (int) $this->user->data['user_id'];
					$this->db->sql_query($sql);


					$this->template->assign_vars(array(
						'REDIRECT'		=> $this->request->variable('redirect', ''),
						'RANDOM'		=> $random,
					));

					page_header('TFA_KEY_REQUIRED');
					$this->template->display('@paul999_tfa/authenticate_main.html');
					page_footer(false); // Do not include cron on this page!
				}
			}
		}
	}
}
