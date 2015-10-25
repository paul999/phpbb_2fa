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
	 * Constructor
	 *
	 * @access public
	 * @param sessionHelperInterface $helper
	 */
	public function __construct(sessionHelperInterface $helper, helper $controller_helper, user $user)
	{
		$this->helper				= $helper;
		$this->controller_helper 	= $controller_helper;
		$this->user					= $user;
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
		);
	}

	public function user_setup_after($event)
	{
		if ($this->user->data['is_bot'] == false && $this->user->data['user_id'] != ANONYMOUS)
		{
			if ($this->helper->isTfaRequired($this->user->data['user_id']))
			{
				$url = '';
				trigger_error($this->user->lang('TFA_REQUIRED_KEY_MISSING', '<a href="' . $url . '">', '</a>'), E_USER_ERROR);
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
			if ($this->helper->isTfaRequired($event['login']['user_row']))
			{
				if (!$this->helper->isTfaRegistered($event['login']['user_row']))
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
