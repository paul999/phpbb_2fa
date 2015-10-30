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

use paul999\tfa\helper\registrationHelper;
use u2flib_server\U2F;

class tfa_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $db, $user, $template;
		global $request, $phpbb_container;

		$user->add_lang('posting');
		$user->add_lang_ext('paul999/tfa', 'ucp_tfa');
		$user->add_lang_ext('paul999/tfa', 'common');

		$registration_table = $phpbb_container->getParameter('paul999.2fa.tables.tfa_registration');

		$submit		= $request->variable('submit', false, false, \phpbb\request\request_interface::POST);
		$error = $data = array();
		$s_hidden_fields = '';

		$u2f = new U2F('https://' . $request->server('HTTP_HOST'));

		add_form_key('ucp_tfa_keys');

		if ($submit)
		{
			$mode = $request->variable('md', '');

			if (!check_form_key('ucp_tfa_keys'))
			{
				$error[] = 'FORM_INVALID';
			}

			switch ($mode) {
				case 'delete':
					if (!sizeof($error))
					{
						$keys = $request->variable('keys', array(''));
						if (!empty($keys))
						{
							foreach ($keys as $key => $id)
							{
								$keys[$key] = $db->sql_like_expression($id . $db->get_any_char());
							}
							$sql_where = '(registration_key ' . implode(' OR registration_key ', $keys) . ')';
							$sql = 'DELETE FROM ' . $registration_table . '
										WHERE user_id = ' . (int) $user->data['user_id'] . '
										AND ' . $sql_where ;

							$db->sql_query($sql);

							meta_refresh(3, $this->u_action);
							$message = $user->lang['TFA_KEYS_DELETED'] . '<br /><br />' . sprintf($user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
							trigger_error($message);
						}
					}
					break;
				case 'register':
					break;

				default:
					$error[] = 'TFA_NO_MODE';
 			}

			// Replace "error" strings with their real, localised form
			$error = array_map(array($user, 'lang'), $error);
		}

		$sql = 'SELECT *
			FROM ' . $registration_table . '
			WHERE user_id = ' . (int) $user->data['user_id'] . '
			ORDER BY registration_id ASC';

		$result = $db->sql_query($sql);
		$rows = array();

		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('keys', array(
			));

			$reg 				= new registrationHelper();
			$reg->counter 		= $row['counter'];
			$reg->certificate	= $row['certificate'];
			$reg->keyHandle		= $row['key_handle'];
			$reg->publicKey 	= $row['public_key'];
			$reg->id 			= $row['id'];
			$rows[] 			= $reg;
		}
		$data = $u2f->getRegisterData($rows);

		$sql_ary = array(
			'u2f_request'	=> json_encode($data[0], JSON_UNESCAPED_SLASHES),
		);

		$sql = 'UPDATE ' . SESSIONS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
					WHERE
						session_id = \'' . $db->sql_escape($user->data['session_id']) . '\' AND
						session_user_id = ' . (int)$user->data['user_id'];
		$db->sql_query($sql);
		$count = $db->sql_affectedrows();

		if ($count != 1)
		{
			if ($count > 1)
			{
				// Reset sessions table. We had multiple sessions with same ID!!!
				$sql_ary['u2f_request'] = '';
				$sql = 'UPDATE ' . SESSIONS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE
								session_id = \'' . $db->sql_escape($user->data['session_id']) . '\' AND
								user_id = ' . (int)$user->data['user_id'];
				$db->sql_query($sql);
			}
			trigger_error('UNABLE_TO_UPDATE_SESSION');
		}

		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'ERROR'		=> (sizeof($error)) ? implode('<br />', $error) : '',

			'L_TITLE'	=> $user->lang['UCP_TFA'],

			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			'S_UCP_ACTION'		=> $this->u_action,
			'U2F_REG'			=> true,
			'U2F_SIGN_REQUEST'	=> json_encode($data[0], JSON_UNESCAPED_SLASHES),
			'U2F_SIGN'			=> json_encode($data[1], JSON_UNESCAPED_SLASHES),
		));

		// Set desired template
		$this->tpl_name = 'ucp_tfa';
		$this->page_title = 'UCP_TFA';
	}
}
