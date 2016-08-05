<?php
/**
*
* 2FA extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Paul Sohier
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace paul999\tfa\controller;

use paul999\tfa\helper\session_helper_interface;
use paul999\tfa\modules\module_interface;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Controller
 */
class main_controller
{
	/**
	 * @var helper
	 */
	private $controller_helper;

	/**
	 * @var template
	 */
	private $template;

	/**
	 * @var driver_interface
	 */
	private $db;

	/**
	 * @var user
	 */
	private $user;

	/**
	 * @var request_interface
	 */
	private $request;

	/**
	 * @var config
	 */
	private $config;

	/**
	 * @var session_helper_interface
	 */
	private $session_helper;

	/**
	 * @var string
	 */
	private $root_path;

	/**
	 * @var string
	 */
	private $php_ext;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param helper $controller_helper
	 * @param driver_interface $db
	 * @param template $template
	 * @param user $user
	 * @param request_interface $request
	 * @param config $config
	 * @param session_helper_interface $session_helper
	 * @param string $root_path
	 * @param string $php_ext
	 */
	public function __construct(helper $controller_helper, driver_interface $db, template $template, user $user, request_interface $request, config $config, session_helper_interface $session_helper, $root_path, $php_ext)
	{
		$this->controller_helper 	= $controller_helper;
		$this->template 			= $template;
		$this->db					= $db;
		$this->user					= $user;
		$this->request				= $request;
		$this->config				= $config;
		$this->session_helper		= $session_helper;
		$this->root_path			= $root_path;
		$this->php_ext				= $php_ext;

	}

	/**
	 * @param int $user_id
	 * @param bool $admin
	 * @param bool $auto_login
	 * @param bool $viewonline
	 * @param string $class
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function display($user_id, $admin, $auto_login, $viewonline, $class)
	{
		$this->user->add_lang_ext('paul999/tfa', 'common');

		if ($this->config['tfa_mode'] == session_helper_interface::MODE_DISABLED)
		{
			throw new AccessDeniedHttpException('TFA_DISABLED');
		}
		if (($this->user->data['user_id'] != ANONYMOUS && !$admin) || $user_id == ANONYMOUS || ($user_id != $this->user->data['user_id'] && $admin))
		{
			throw new AccessDeniedHttpException('TFA_NO_ACCESS');
		}
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
					$module = $row;
					break;
				}
			}
		}
		if ($module == null || !($module instanceof module_interface))
		{
			throw new BadRequestHttpException($this->user->lang('TFA_SOMETHING_WENT_WRONG'));
		}

		/**
		 * @var module_interface $row
		 */
		foreach ($modules as $row)
		{
			if ($row->is_usable($user_id))
			{
				$this->template->assign_block_vars('', array(
					'U_CHANGE_CLASS'	=> $this->controller_helper->route('paul999_tfa_read_controller', array(
						'user_id'		=> $user_id,
						'admin'			=> $admin,
						'auto_login'	=> $auto_login,
						'viewonline'	=> $viewonline,
						'class'			=> $row->get_name(),
					)),
				));
			}
		}
		$module->login_start($user_id);

		$this->template->assign_vars(array(
			'REDIRECT'		=> $this->request->variable('redirect', ''),
			'U_SUBMIT_AUTH'	=> $this->controller_helper->route('paul999_tfa_read_controller_submit', array(
				'user_id'		=> $user_id,
				'admin'			=> $admin,
				'auto_login'	=> $auto_login,
				'viewonline'	=> $viewonline,
				'class'			=> $module->get_name(),
			)),
		));

		return $this->controller_helper->render('@paul999_tfa/authenticate_main.html');
	}

	/**
	 * @param int $user_id
	 * @param bool $admin
	 * @param bool $auto_login
	 * @param bool $viewonline
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws AccessDeniedHttpException
	 */
	public function submit($user_id, $admin, $auto_login, $viewonline, $class)
	{
		$this->user->add_lang_ext('paul999/tfa', 'common');

		if (empty($class))
		{
			throw new BadRequestHttpException($this->user->lang('TFA_SOMETHING_WENT_WRONG'));
		}

		$module = $this->session_helper->findModule($class);

		if ($module == null)
		{
			throw new BadRequestHttpException($this->user->lang('TFA_SOMETHING_WENT_WRONG'));
		}
		$module->login($user_id);

		$old_session_id = $this->user->session_id;

		if ($admin)
		{
			$cookie_expire = time() - 31536000;
			$this->user->set_cookie('u', '', $cookie_expire);
			$this->user->set_cookie('sid', '', $cookie_expire);
		}

		$result = $this->user->session_create($user_id, $admin, $auto_login, $viewonline);

		// Successful session creation
		if ($result === true)
		{
			// If admin re-authentication we remove the old session entry because a new one has been created...
			if ($admin)
			{
				// the login array is used because the user ids do not differ for re-authentication
				$sql = 'DELETE FROM ' . SESSIONS_TABLE . "
						WHERE session_id = '" . $this->db->sql_escape($old_session_id) . "'
						AND session_user_id = " . (int) $user_id;
				$this->db->sql_query($sql);

				redirect(append_sid("{$this->root_path}adm/index.{$this->php_ext}", false, true, $this->user->data['session_id']));
			}
			$redirect = $this->request->variable('redirect', "{$this->root_path}/index.{$this->php_ext}");
			redirect(append_sid($redirect, false, true, $this->user->data['session_id']));
		}
		throw new BadRequestHttpException($this->user->lang('TFA_SOMETHING_WENT_WRONG'));
	}
}
