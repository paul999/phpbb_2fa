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

use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;
use paul999\tfa\helper\registrationHelper;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use const u2flib_server\ERR_ATTESTATION_SIGNATURE;
use const u2flib_server\ERR_ATTESTATION_VERIFICATION;
use const u2flib_server\ERR_AUTHENTICATION_FAILURE;
use const u2flib_server\ERR_BAD_RANDOM;
use const u2flib_server\ERR_BAD_UA_RETURNING;
use const u2flib_server\ERR_COUNTER_TOO_LOW;
use const u2flib_server\ERR_NO_MATCHING_REGISTRATION;
use const u2flib_server\ERR_NO_MATCHING_REQUEST;
use const u2flib_server\ERR_OLD_OPENSSL;
use const u2flib_server\ERR_PUBKEY_DECODE;
use const u2flib_server\ERR_UNMATCHED_CHALLENGE;
use u2flib_server\Error;
use u2flib_server\U2F;

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
	public function __construct(helper $controller_helper, driver_interface $db, template $template, user $user, request_interface $request, $root_path, $php_ext, $user_table, $registration_table)
	{
		$this->controller_helper 	= $controller_helper;
		$this->template 			= $template;
		$this->db					= $db;
		$this->user					= $user;
		$this->request				= $request;
		$this->root_path			= $root_path;
		$this->php_ext				= $php_ext;
		$this->user_table			= $user_table;
		$this->registration_table	= $registration_table;

		$scheme = $this->request->is_secure() ? 'https://' : 'http://';
		$this->u2f = new U2F($scheme . $this->request->server('HTTP_HOST'));
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
		if ($this->user->data['user_id'] != ANONYMOUS || $user_id == ANONYMOUS)
		{
			throw new AccessDeniedHttpException();
		}

		$sql = 'SELECT username FROM ' . $this->user_table . ' WHERE user_id = ' . (int)$user_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row)
		{
			throw new AccessDeniedHttpException();
		}

		$registrations = json_encode($this->u2f->getAuthenticateData($this->getRegistrations($user_id)));

		$sql_ary = array(
			'u2f_request'	=> $registrations
		);

		$sql = 'UPDATE ' . SESSIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
					WHERE
						session_id = \'' . $this->db->sql_escape($this->user->data['session_id']) . '\' AND
						session_user_id = ' . (int)$this->user->data['user_id'];
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
						user_id = ' . (int)$this->user->data['user_id'];
				$this->db->sql_query($sql);
			}
			throw new BadRequestHttpException('UNABLE_TO_UPDATE_SESSION');
		}

		$this->template->assign_vars(array(
			'USERNAME'		=> $row['username'],
			'U2F_REQ'		=> $registrations,
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
		$sql = 'SELECT u2f_request FROM ' . SESSIONS_TABLE . ' WHERE
			session_id = \'' . $this->db->sql_escape($this->user->data['session_id']) . '\' AND
  			session_user_id = ' . (int)$this->user->data['user_id'];
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row)
		{
			throw new AccessDeniedHttpException();
		}

		try {
			/** @var \paul999\tfa\helper\registrationHelper $reg */
			$reg = $this->u2f->doAuthenticate(json_decode($row['u2f_request']), $this->getRegistrations($user_id), json_decode($this->request->variable('authenticate', '')));
			$sql_ary = array(
				'counter'	=> $reg->counter,
			);

			$sql = 'UPDATE ' . $this->registration_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE registration_id = ' . (int)$reg->id;
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
				/**
				 * TODO: Find some proper way for redirect!
				 */
				redirect(append_sid("{$this->root_path}/index.{$this->php_ext}", false, true, $this->user->data['session_id']));
			}
			throw new BadRequestHttpException('TFA_SOMETHING_WENT_WRONG');
		}
		catch (Error $error)
		{
			switch ($error->getCode()) {
				/** Error for the authentication message not matching any outstanding
				 * authentication request */
				case ERR_NO_MATCHING_REQUEST:
					throw new BadRequestHttpException('ERR_NO_MATCHING_REQUEST', $error);

				/** Error for the authentication message not matching any registration */
				case ERR_NO_MATCHING_REGISTRATION:
					throw new BadRequestHttpException('ERR_NO_MATCHING_REGISTRATION', $error);

				/** Error for the signature on the authentication message not verifying with
				 * the correct key */
				case ERR_AUTHENTICATION_FAILURE:
					throw new BadRequestHttpException('ERR_AUTHENTICATION_FAILURE', $error);

				/** Error for the challenge in the registration message not matching the
				 * registration challenge */
				case ERR_UNMATCHED_CHALLENGE:
					throw new BadRequestHttpException('ERR_UNMATCHED_CHALLENGE', $error);

				/** Error for the attestation signature on the registration message not
				 * verifying */
				case ERR_ATTESTATION_SIGNATURE:
					throw new BadRequestHttpException('ERR_ATTESTATION_SIGNATURE', $error);

				/** Error for the attestation verification not verifying */
				case ERR_ATTESTATION_VERIFICATION:
					throw new BadRequestHttpException('ERR_ATTESTATION_VERIFICATION', $error);

				/** Error for not getting good random from the system */
				case ERR_BAD_RANDOM:
					throw new BadRequestHttpException('ERR_BAD_RANDOM', $error);

				/** Error when the counter is lower than expected */
				case ERR_COUNTER_TOO_LOW:
					throw new BadRequestHttpException('ERR_COUNTER_TOO_LOW', $error);

				/** Error decoding public key */
				case ERR_PUBKEY_DECODE:
					throw new BadRequestHttpException('ERR_PUBKEY_DECODE', $error);

				/** Error user-agent returned error */
				case ERR_BAD_UA_RETURNING:
					throw new BadRequestHttpException('ERR_BAD_UA_RETURNING', $error);

				/** Error old OpenSSL version */
				case ERR_OLD_OPENSSL:
					throw new BadRequestHttpException('ERR_OLD_OPENSSL', $error);

				default:
					throw new BadRequestHttpException('UNKNOWN_ERROR', $error);
			}
		}
		catch (\InvalidArgumentException $invalid)
		{
			throw new BadRequestHttpException('TFA_SOMETHING_WENT_WRONG', $invalid); // TODO: Language
		}

	}


	/**
	 * Select all registration objects from the database
	 * @param $user_id
	 * @return array
	 */
	private function getRegistrations($user_id)
	{
		$sql = 'SELECT * FROM ' . $this->registration_table . ' WHERE user_id = ' . (int)$user_id;
		$result = $this->db->sql_query($sql);
		$rows = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			$reg 				= new registrationHelper();
			$reg->counter 		= $row['counter'];
			$reg->certificate	= $row['certificate'];
			$reg->keyHandle		= $row['key_handle'];
			$reg->publicKey 	= $row['public_key'];
			$reg->id 			= $row['id'];
			$rows[] 			= $reg;
		}

		$this->db->sql_freeresult($result);
		return $rows;
	}
}
