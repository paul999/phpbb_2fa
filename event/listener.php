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
	 * Constructor
	 *
	 * @access public
	 * @param sessionHelperInterface $helper
	 */
	public function __construct(sessionHelperInterface $helper, helper $controller_helper)
	{
		$this->helper = $helper;
		$this->controller_helper = $controller_helper;
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
		);
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
						'user_id'		=> $event['login']['user_row']['user_id'],
						'admin'			=> $event['admin'],
						'auto_login'	=> $event['auto_login'],
						'viewonline'	=> 0,
					)));
				}
			}
		}
	}
}
