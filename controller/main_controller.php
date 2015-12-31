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
use paul999\u2f\Exceptions\U2fError;
use paul999\u2f\U2F;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;
use paul999\tfa\helper\registration_helper;
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
	 * @var string
	 */
	private $root_path;

	/**
	 * @var string
	 */
	private $php_ext;
	/**
	 * @var string
	 */
	private $user_table;

	/**
	 * @var string
	 */
	private $registration_table;

	/**
	 * @var U2F
	 */
	private $u2f;

	/**
	 * Constructor
	 *
	 * @access public
	 * @param helper $controller_helper
	 * @param driver_interface $db
	 * @param template $template
	 * @param user $user
	 * @param request_interface $request
	 * @param string $root_path
	 * @param string$php_ext
	 * @param string $user_table
	 * @param string $registration_table
	 */
	public function __construct(helper $controller_helper, driver_interface $db, template $template, user $user, request_interface $request, config $config, $root_path, $php_ext, $user_table, $registration_table)
	{
		$this->controller_helper 	= $controller_helper;
		$this->template 			= $template;
		$this->db					= $db;
		$this->user					= $user;
		$this->request				= $request;
		$this->config				= $config;
		$this->root_path			= $root_path;
		$this->php_ext				= $php_ext;
		$this->user_table			= $user_table;
		$this->registration_table	= $registration_table;

		$this->u2f = new U2F('https://' . $this->request->server('HTTP_HOST'));
	}

	/**
	 * @param int $user_id
	 * @param bool $admin
	 * @param bool $auto_login
	 * @param bool $viewonline
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function display($user_id, $admin, $auto_login, $viewonline)
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

		$registrations = json_encode($this->u2f->getAuthenticateData($this->getRegistrations($user_id)), JSON_UNESCAPED_SLASHES);

		$sql_ary = array(
			'u2f_request'	=> $registrations
		);

		$sql = 'UPDATE ' . SESSIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
					WHERE
						session_id = \'' . $this->db->sql_escape($this->user->data['session_id']) . '\' AND
						session_user_id = ' . (int) $this->user->data['user_id'];
		$this->db->sql_query($sql);
		$count = $this->db->sql_affectedrows();

		if ($count != 1)
		{
			if ($count > 1)
			{
				// Reset sessions table. We had multiple sessions with same ID!!!
				$sql_ary['u2f_request'] = '';
				$sql = 'UPDATE ' . SESSIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
					WHERE
						session_id = \'' . $this->db->sql_escape($this->user->data['session_id']) . '\' AND
						session_user_id = ' . (int) $this->user->data['user_id'];
				$this->db->sql_query($sql);
			}
			throw new BadRequestHttpException('TFA_UNABLE_TO_UPDATE_SESSION');
		}

		$this->template->assign_vars(array(
			'U2F_REQ'		=> $registrations,
			'REDIRECT'		=> $this->request->variable('redirect', ''),
			'U_SUBMIT_AUTH'	=> $this->controller_helper->route('paul999_tfa_read_controller_submit', array(
				'user_id'		=> $user_id,
				'admin'			=> $admin,
				'auto_login'	=> $auto_login,
				'viewonline'	=> $viewonline,
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
	public function submit($user_id, $admin, $auto_login, $viewonline)
	{
		$this->user->add_lang_ext('paul999/tfa', 'common');

		$sql = 'SELECT u2f_request FROM ' . SESSIONS_TABLE . ' WHERE
			session_id = \'' . $this->db->sql_escape($this->user->data['session_id']) . '\' AND
			session_user_id = ' . (int) $this->user->data['user_id'];
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row || empty($row['u2f_request']))
		{
			throw new AccessDeniedHttpException($this->user->lang('TFA_NO_ACCESS'));
		}

		try
		{
			$response = json_decode(htmlspecialchars_decode($this->request->variable('authenticate', '')));

			if (property_exists( $response, 'errorCode'))
			{
				if ($response->errorCode == 4) // errorCode 4 means that this device wasn't registered
				{
					throw new AccessDeniedHttpException($this->user->lang('TFA_NOT_REGISTERED'));
				}
			}

			/** @var \paul999\tfa\helper\registration_helper $reg */
			$reg = $this->u2f->doAuthenticate(json_decode($row['u2f_request']), $this->getRegistrations($user_id), $response);
			$sql_ary = array(
				'counter'	=> $reg->getCounter(),
				'last_used'	=> time(),
			);

			$sql = 'UPDATE ' . $this->registration_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE registration_id = ' . (int) $reg->id;
			$this->db->sql_query($sql);

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
							AND session_user_id = {$user_id}";
					$this->db->sql_query($sql);

					redirect(append_sid("{$this->root_path}adm/index.{$this->php_ext}", false, true, $this->user->data['session_id']));
				}
				$redirect = $this->request->variable('redirect', "{$this->root_path}/index.{$this->php_ext}");
				redirect(append_sid($redirect, false, true, $this->user->data['session_id']));
			}
			throw new BadRequestHttpException($this->user->lang('TFA_SOMETHING_WENT_WRONG'));
		}
		catch (U2fError $error)
		{
			$this->createError($error);
		}
		catch (\InvalidArgumentException $invalid)
		{
			throw new BadRequestHttpException($this->user->lang('TFA_SOMETHING_WENT_WRONG') . '<br />' . $invalid->getMessage(), $invalid);
		}

	}


	/**
	 * Select all registration objects from the database
	 * @param integer $user_id
	 * @return array
	 */
	private function getRegistrations($user_id)
	{
		$sql = 'SELECT * FROM ' . $this->registration_table . ' WHERE user_id = ' . (int) $user_id;
		$result = $this->db->sql_query($sql);
		$rows = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			$reg 				= new registration_helper();
			$reg->setCounter($row['counter']);
			$reg->setCertificate($row['certificate']);
			$reg->setKeyHandle($row['key_handle']);
			$reg->setPublicKey($row['public_key']);
			$reg->id 			= $row['registration_id'];
			$rows[] 			= $reg;
		}

		$this->db->sql_freeresult($result);
		return $rows;
	}

	/**
	 * @param U2fError $error
	 */
	private function createError(U2fError $error)
	{
		switch ($error->getCode())
		{
			/** Error for the authentication message not matching any outstanding
			 * authentication request */
			case U2fError::ERR_NO_MATCHING_REQUEST:
				throw new BadRequestHttpException($this->user->lang('ERR_NO_MATCHING_REQUEST'), $error);

			/** Error for the authentication message not matching any registration */
			case U2fError::ERR_NO_MATCHING_REGISTRATION:
				throw new BadRequestHttpException($this->user->lang('ERR_NO_MATCHING_REGISTRATION'), $error);

			/** Error for the signature on the authentication message not verifying with
			 * the correct key */
			case U2fError::ERR_AUTHENTICATION_FAILURE:
				throw new BadRequestHttpException($this->user->lang('ERR_AUTHENTICATION_FAILURE'), $error);

			/** Error for the challenge in the registration message not matching the
			 * registration challenge */
			case U2fError::ERR_UNMATCHED_CHALLENGE:
				throw new BadRequestHttpException($this->user->lang('ERR_UNMATCHED_CHALLENGE'), $error);

			/** Error for the attestation signature on the registration message not
			 * verifying */
			case U2fError::ERR_ATTESTATION_SIGNATURE:
				throw new BadRequestHttpException($this->user->lang('ERR_ATTESTATION_SIGNATURE'), $error);

			/** Error for the attestation verification not verifying */
			case U2fError::ERR_ATTESTATION_VERIFICATION:
				throw new BadRequestHttpException($this->user->lang('ERR_ATTESTATION_VERIFICATION'), $error);

			/** Error for not getting good random from the system */
			case U2fError::ERR_BAD_RANDOM:
				throw new BadRequestHttpException($this->user->lang('ERR_BAD_RANDOM'), $error);

			/** Error when the counter is lower than expected */
			case U2fError::ERR_COUNTER_TOO_LOW:
				throw new BadRequestHttpException($this->user->lang('ERR_COUNTER_TOO_LOW'), $error);

			/** Error decoding public key */
			case U2fError::ERR_PUBKEY_DECODE:
				throw new BadRequestHttpException($this->user->lang('ERR_PUBKEY_DECODE'), $error);

			/** Error user-agent returned error */
			case U2fError::ERR_BAD_UA_RETURNING:
				throw new BadRequestHttpException($this->user->lang('ERR_BAD_UA_RETURNING'), $error);

			/** Error old OpenSSL version */
			case U2fError::ERR_OLD_OPENSSL:
				throw new BadRequestHttpException(sprintf($this->user->lang('ERR_OLD_OPENSSL'), OPENSSL_VERSION_TEXT), $error);

			default:
				throw new BadRequestHttpException($this->user->lang('TFA_UNKNOWN_ERROR'), $error);
		}
	}
}
