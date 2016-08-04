<?php
/**
 *
 * 2FA extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 Paul Sohier
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\tfa\modules;

use paul999\tfa\helper\registration_helper;
use paul999\u2f\AuthenticationResponse;
use paul999\u2f\Exceptions\U2fError;
use paul999\u2f\RegisterRequestInterface;
use paul999\u2f\SignRequest;
use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;
use phpbrowscap\Browscap;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class u2f implements module_interface
{

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
	 * @var template
	 */
	private $template;

	/**
	 * @var string
	 */
	private $registration_table;

	/**
	 * @var string
	 */
	private $root_path;

	/**
	 * @var \paul999\u2f\U2F
	 */
	private $u2f;

	/**
	 * u2f constructor.
	 * @param driver_interface $db
	 * @param user $user
	 * @param request_interface $request
	 * @param template $template
	 * @param string $registration_table
	 * @param string $root_path
	 */
	public function __construct(driver_interface $db, user $user, request_interface $request, template $template, $registration_table, $root_path)
	{
		$this->db       = $db;
		$this->user     = $user;
		$this->request  = $request;
		$this->template = $template;
		$this->root_path= $root_path;

		$this->registration_table	= $registration_table;

		$this->u2f = new \paul999\u2f\U2F('https://' . $this->request->server('HTTP_HOST'));
	}

	/**
	 * Return if this module is enabled by the admin
	 * (And all server requirements are met).
	 *
	 * Do not return false in case a specific user disabeld this module,
	 * OR if the user is unable to use this specific module.
	 * @return boolean
	 */
	public function is_enabled()
	{
		return true;
	}

	/**
	 * Check if the current user is able to use this module.
	 *
	 * This means that the user enabled it in the UCP,
	 * And has it setup up correctly.
	 * This method will be called during login, not during registration/
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public function is_usable($user_id)
	{
		if (!$this->is_potentially_usable($user_id))
		{
			return false;
		}
		$sql = 'SELECT COUNT(registration_id) as reg_id 
					FROM ' . $this->registration_table . ' 
					WHERE 
						user_id = ' . (int) $user_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row && $row['reg_id'] > 0;
	}

	/**
	 * Check if the user can potentially use this.
	 * This method is called at registration page.
	 *
	 * You can, for example, check if the current browser is suitable.
	 *
	 * @param int|boolean $user_id Use false to ignore user
	 * @return bool
	 */
	public function is_potentially_usable($user_id = false)
	{
		$browsercap = new Browscap($this->root_path . 'cache/');
		$info = $browsercap->getBrowser($this->request->server('HTTP_USER_AGENT'));
		return strtolower($info->Browser) === 'chrome' && $this->is_ssl();
	}

	/**
	 * Check if the current session is secure.
	 *
	 * @return bool
	 */
	private function is_ssl()
	{
		$secure = $this->request->server('HTTPS');
		if (!empty($secure))
		{
			return 'on' == strtolower($secure) || '1' == $secure;
		}
		else if ('443' == $this->request->server('SERVER_PORT'))
		{
			return true;
		}
		return false;
	}

	/**
	 * Get the priority for this module.
	 * A lower priority means more chance it gets selected as default option
	 *
	 * There can be only one module with a specific priority!
	 * If there is already a module registered with this priority,
	 * a Exception might be thrown
	 *
	 * @return int
	 */
	public function get_priority()
	{
		return 10;
	}

	/**
	 * Start of the login procedure.
	 * @param int $user_id
	 * @return void
	 * @throws BadRequestHttpException
	 */
	public function login_start($user_id)
	{
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

		$this->template->assign_var('U2F_REQ', $registrations);
	}

	/**
	 * Actual login procedure
	 * @param int $user_id
	 * @throws AccessDeniedHttpException
	 */
	public function login($user_id)
	{
		try
		{
			$sql = 'SELECT u2f_request 
						FROM ' . SESSIONS_TABLE . ' 
						WHERE
							session_id = \'' . $this->db->sql_escape($this->user->data['session_id']) . '\' AND
							session_user_id = ' . (int) $this->user->data['user_id'];
			$result = $this->db->sql_query($sql);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if (!$row || empty($row['u2f_request']))
			{
				throw new AccessDeniedHttpException($this->user->lang('TFA_NO_ACCESS'));
			}

			$response = json_decode(htmlspecialchars_decode($this->request->variable('authenticate', '')));

			if (property_exists($response, 'errorCode'))
			{
				if ($response->errorCode == 4) // errorCode 4 means that this device wasn't registered
				{
					throw new AccessDeniedHttpException($this->user->lang('TFA_NOT_REGISTERED'));
				}
				throw new BadRequestHttpException($this->user->lang('TFA_SOMETHING_WENT_WRONG'));
			}
			$result = new AuthenticationResponse($response->signatureData, $response->clientData, $response->keyHandle, $response->errorCode);

			/** @var \paul999\tfa\helper\registration_helper $reg */
			$reg = $this->u2f->doAuthenticate($this->convertRequests(json_decode($row['u2f_request'])), $this->getRegistrations($user_id), $result);
			$sql_ary = array(
				'counter' => $reg->getCounter(),
				'last_used' => time(),
			);

			$sql = 'UPDATE ' . $this->registration_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE registration_id = ' . (int) $reg->getId();
			$this->db->sql_query($sql);
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
	 * @param array $requests
	 * @return array
	 */
	private function convertRequests($requests)
	{
		$result = array();
		foreach ($requests as $request)
		{
			$result[] = new SignRequest($request->challenge, $request->keyHandle, $request->appId);
		}
		return $result;
	}

	/**
	 * Start of registration
	 * @return string
	 */
	public function register_start()
	{
		$sql = 'SELECT *
			FROM ' . $this->registration_table . '
			WHERE user_id = ' . (int) $this->user->data['user_id'] . '
			ORDER BY registration_id ASC';

		$result = $this->db->sql_query($sql);
		$reg_data = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			$reg = new registration_helper();
			$reg->setCounter($row['counter']);
			$reg->setCertificate($row['certificate']);
			$reg->setKeyHandle($row['key_handle']);
			$reg->setPublicKey($row['public_key']);
			$reg->setId($row['registration_id']);

			$reg_data[] = $reg;
		}
		$this->db->sql_freeresult($result);

		$data = $this->u2f->getRegisterData($reg_data);

		$sql_ary = array(
			'u2f_request' => json_encode($data[0], JSON_UNESCAPED_SLASHES),
		);

		$count = $this->update_session($sql_ary);

		if ($count == 0)
		{
			trigger_error('TFA_UNABLE_TO_UPDATE_SESSION');
		}
		else if ($count > 1)
		{
			// Reset sessions table. We had multiple sessions with same ID!!!
			$sql_ary['u2f_request'] = '';
			$this->update_session($sql_ary);

			trigger_error('TFA_UNABLE_TO_UPDATE_SESSION');
		}

		$this->template->assign_vars(array(
			'U2F_REG'           => true,
			'U2F_SIGN_REQUEST'  => json_encode($data[0], JSON_UNESCAPED_SLASHES),
			'U2F_SIGN'          => json_encode($data[1], JSON_UNESCAPED_SLASHES),
		));

		return 'tfa_u2f_ucp_new';
	}

	/**
	 * Actual registration
	 * @return void
	 * @throws BadRequestHttpException
	 */
	public function register()
	{
		try
		{
			$data = json_decode($this->user->data['u2f_request']);

			$reg = $this->u2f->doRegister($data, json_decode(htmlspecialchars_decode($this->request->variable('register', ''))));

			$sql_ary = array(
				'user_id' => $this->user->data['user_id'],
				'key_handle' => $reg->getKeyHandle(),
				'public_key' => $reg->getPublicKey(),
				'certificate' => $reg->getCertificate(),
				'counter' => ($reg->getCounter() > 0) ? $reg->getCounter() : 0,
				'registered' => time(),
				'last_used' => time(),
			);

			$sql = 'INSERT INTO ' . $this->registration_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
			$this->db->sql_query($sql);

			$sql_ary = array(
				'u2f_request' => '',
			);

			$this->update_session($sql_ary);
		}
		catch (U2fError $err)
		{
			$this->createError($err);
		}
	}

	/**
	 * This method is called to show the UCP page.
	 * You can assign template variables to the template, or do anything else here.
	 */
	public function show_ucp()
	{
		$sql = 'SELECT *
			FROM ' . $this->registration_table . '
			WHERE user_id = ' . (int) $this->user->data['user_id'] . '
			ORDER BY registration_id ASC';

		$result = $this->db->sql_query($sql);
		//$this->reg_data = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('keys', array(
				'CLASS'         => $this->get_name(),
				'ID'            => $row['registration_id'],
				'REGISTERED'    => $this->user->format_date($row['registered']),
				'LAST_USED'     => $this->user->format_date($row['last_used']),
			));
		}
		$this->db->sql_freeresult($result);
	}

	/**
	 * Delete a specific row from the UCP.
	 * The data is based on the data provided in show_ucp.
	 * @param int $key
	 * @return void
	 */
	public function delete($key)
	{
			$sql = 'DELETE FROM ' . $this->registration_table . '
						WHERE user_id = ' . (int) $this->user->data['user_id'] . '
						AND registration_id =' . (int) $key;

			$this->db->sql_query($sql);

	}

	/**
	 * If this module can add new keys (Or other things)
	 *
	 * @return boolean
	 */
	public function can_register()
	{
		return $this->is_potentially_usable(false);
	}

	/**
	 * Return the name of the current module
	 * This is for internal use only
	 * @return string
	 */
	public function get_name()
	{
		return 'u2f';
	}

	/**
	 * Get a language key for this specific module.
	 * @return string
	 */
	public function get_translatable_name()
	{
		return 'MODULE_U2F';
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
			$reg = new registration_helper();
			$reg->setCounter($row['counter']);
			$reg->setCertificate($row['certificate']);
			$reg->setKeyHandle($row['key_handle']);
			$reg->setPublicKey($row['public_key']);
			$reg->setId($row['registration_id']);

			$rows[] = $reg;
		}

		$this->db->sql_freeresult($result);
		return $rows;
	}

	/**
	 * @param U2fError $error
	 * @throws BadRequestHttpException
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

	/**
	 * Update the session with new TFA data
	 * @param $sql_ary
	 * @return int
	 */
	private function update_session($sql_ary)
	{
		$sql = 'UPDATE ' . SESSIONS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE
								session_id = \'' . $this->db->sql_escape($this->user->data['session_id']) . '\' AND
								session_user_id = ' . (int) $this->user->data['user_id'];
		$this->db->sql_query($sql);

		return $this->db->sql_affectedrows();
	}
}
