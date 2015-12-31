<?php
/**
 *
 * 2FA extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 Paul Sohier
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\tfa\ucp;

use paul999\tfa\helper\registration_helper;
use paul999\u2f\Exceptions\U2fError;
use paul999\u2f\U2F;
use phpbb\db\driver\driver_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;

class tfa_module
{
	/**
	 * @var string
	 */
	public $u_action;

	/**
	 * @var  string
	 */
	public $page_title;

	/**
	 * @var string
	 */
	public $tpl_name;

	/**
	 * @var U2F
	 */
	private $u2f;

	/**
	 * @var string
	 */
	private $registration_table;

	/**
	 * @var driver_interface
	 */
	private $db;

	/**
	 * @var user
	 */
	private $user;

	/**
	 * @var template
	 */
	private $template;

	/**
	 * @var request_interface
	 */
	private $request;

	/**
	 * @param string $registration_table
	 * @param driver_interface $db
	 * @param user $user
	 * @param template $template
	 * @param request_interface $request
	 */
	private function setup($registration_table, driver_interface $db, user $user, template $template, request_interface $request)
	{
		$this->registration_table = $registration_table;
		$this->db = $db;
		$this->user = $user;
		$this->template = $template;
		$this->request = $request;
	}

	/**
	 * @param $id
	 * @param $mode
	 */
	public function main($id, $mode)
	{
		global $db, $user, $template;
		global $request, $phpbb_container;

		$user->add_lang('posting');
		$user->add_lang_ext('paul999/tfa', 'ucp_tfa');
		$user->add_lang_ext('paul999/tfa', 'common');

		$this->setup($phpbb_container->getParameter('paul999.2fa.tables.tfa_registration'), $db, $user, $template, $request);

		$this->createPage();
	}

	/**
	 * @param array $error
	 */
	private function register_security_key(&$error)
	{
		try
		{
			$reg = $this->u2f->doRegister(json_decode($this->user->data['u2f_request']), json_decode(htmlspecialchars_decode($this->request->variable('register', ''))));

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

			meta_refresh(3, $this->u_action);
			$message = $this->user->lang['TFA_KEY_ADDED'] . '<br /><br />' . sprintf($this->user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
			trigger_error($message);

		}
		catch (U2fError $err)
		{
			$this->createError($err, $error);
		}
		catch (\InvalidArgumentException $e)
		{
			$error[] = $e->getMessage();
		}
	}

	/**
	 *
	 */
	private function createPage()
	{
		$submit = $this->request->variable('md', false, false, \phpbb\request\request_interface::POST);
		$error = array();
		$s_hidden_fields = '';

		$this->u2f = new U2F('https://' . $this->request->server('HTTP_HOST'));

		add_form_key('ucp_tfa_keys');

		if ($submit)
		{
			$mode = $this->request->variable('md', '');
			if (!check_form_key('ucp_tfa_keys'))
			{
				$error[] = 'FORM_INVALID';
			}
			else
			{
				switch ($mode)
				{
					case 'delete':
						$this->delete_keys();
						break;

					case 'register':
						$this->register_security_key($error);
					break;

					default:
						$error[] = 'TFA_NO_MODE';
				}
			}

			// Replace "error" strings with their real, localised form
			$error = array_map(array($this->user, 'lang'), $error);
		}

		$sql = 'SELECT *
			FROM ' . $this->registration_table . '
			WHERE user_id = ' . (int) $this->user->data['user_id'] . '
			ORDER BY registration_id ASC';

		$result = $this->db->sql_query($sql);
		$rows = array();

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('keys', array(
				'ID' => $row['registration_id'],
				'REGISTERED' => $this->user->format_date($row['registered']),
				'LAST_USED' => $this->user->format_date($row['last_used']),
			));

			$reg				= new registration_helper();
			$reg->setCounter($row['counter']);
			$reg->setCertificate($row['certificate']);
			$reg->setKeyHandle($row['key_handle']);
			$reg->setPublicKey($row['public_key']);
			$reg->id			= $row['registration_id'];
			$rows[]				= $reg;
		}
		$data = $this->u2f->getRegisterData($rows);

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

		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'ERROR' => (sizeof($error)) ? implode('<br />', $error) : '',

			'L_TITLE' => $this->user->lang['UCP_TFA'],

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_UCP_ACTION' => $this->u_action,
			'U2F_REG' => true,
			'U2F_SIGN_REQUEST' => json_encode($data[0], JSON_UNESCAPED_SLASHES),
			'U2F_SIGN' => json_encode($data[1], JSON_UNESCAPED_SLASHES),
		));

		// Set desired template
		$this->tpl_name = 'ucp_tfa';
		$this->page_title = 'UCP_TFA';
	}

	/**
	 *
	 */
	private function delete_keys()
	{
		$keys = $this->request->variable('keys', array(0));
		if (!empty($keys))
		{
			$sql_where = $this->db->sql_in_set('registration_id', $keys);
			$sql = 'DELETE FROM ' . $this->registration_table . '
										WHERE user_id = ' . (int) $this->user->data['user_id'] . '
										AND ' . $sql_where;

			$this->db->sql_query($sql);

			meta_refresh(3, $this->u_action);
			$message = $this->user->lang['TFA_KEYS_DELETED'] . '<br /><br />' . sprintf($this->user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
			trigger_error($message);
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

	/**
	 * @param U2fError $err
	 * @param array $error
	 */
	private function createError(U2fError $err, &$error)
	{
		switch ($err->getCode())
		{
			/** Error for the authentication message not matching any outstanding
			 * authentication request */
			case U2fError::ERR_NO_MATCHING_REQUEST:
				$error[] = 'ERR_NO_MATCHING_REQUEST';
				break;
			/** Error for the authentication message not matching any registration */
			case U2fError::ERR_NO_MATCHING_REGISTRATION:
				$error[] = 'ERR_NO_MATCHING_REGISTRATION';
				break;
			/** Error for the signature on the authentication message not verifying with
			 * the correct key */
			case U2fError::ERR_AUTHENTICATION_FAILURE:
				$error[] = 'ERR_AUTHENTICATION_FAILURE';
				break;
			/** Error for the challenge in the registration message not matching the
			 * registration challenge */
			case U2fError::ERR_UNMATCHED_CHALLENGE:
				$error[] = 'ERR_UNMATCHED_CHALLENGE';
				break;
			/** Error for the attestation signature on the registration message not
			 * verifying */
			case U2fError::ERR_ATTESTATION_SIGNATURE:
				$error[] = 'ERR_ATTESTATION_SIGNATURE';
				break;
			/** Error for the attestation verification not verifying */
			case U2fError::ERR_ATTESTATION_VERIFICATION:
				$error[] = 'ERR_ATTESTATION_VERIFICATION';
				break;
			/** Error for not getting good random from the system */
			case U2fError::ERR_BAD_RANDOM:
				$error[] = 'ERR_BAD_RANDOM';
				break;
			/** Error when the counter is lower than expected */
			case U2fError::ERR_COUNTER_TOO_LOW:
				$error[] = 'ERR_COUNTER_TOO_LOW';
				break;
			/** Error decoding public key */
			case U2fError::ERR_PUBKEY_DECODE:
				$error[] = 'ERR_PUBKEY_DECODE';
				break;
			/** Error user-agent returned error */
			case U2fError::ERR_BAD_UA_RETURNING:
				$error[] = 'ERR_BAD_UA_RETURNING';
				break;
			/** Error old OpenSSL version */
			case U2fError::ERR_OLD_OPENSSL:
				$error[] = sprintf('ERR_OLD_OPENSSL', OPENSSL_VERSION_TEXT);
				break;
			default:
				$error[] = 'TFA_UNKNOWN_ERROR';
		}
	}
}
