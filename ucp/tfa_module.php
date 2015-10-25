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

class tfa_module
{
	function main($id, $mode)
	{
		global $cache, $config, $db, $user, $auth, $template, $phpbb_root_path, $phpEx;
		global $request, $phpbb_container, $phpbb_dispatcher;

		$user->add_lang('posting');
		$user->add_lang_ext('paul999/tfa', 'ucp_tfa');

		$registration_table = $phpbb_container->get('%paul999.2fa.tables.tfa_registration%');

		$preview	= $request->variable('preview', false, false, \phpbb\request\request_interface::POST);
		$submit		= $request->variable('submit', false, false, \phpbb\request\request_interface::POST);
		$delete		= $request->variable('delete', false, false, \phpbb\request\request_interface::POST);
		$error = $data = array();
		$s_hidden_fields = '';


		add_form_key('ucp_tfa_keys');

		if ($submit)
		{
			$keys = $request->variable('keys', array(''));

			if (!check_form_key('ucp_tfa_keys'))
			{
				$error[] = 'FORM_INVALID';
			}

			if (!sizeof($error))
			{
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

			// Replace "error" strings with their real, localised form
			$error = array_map(array($user, 'lang'), $error);
		}

		$sql = 'SELECT *
			FROM ' . $registration_table . '
			WHERE user_id = ' . (int) $user->data['user_id'] . '
			ORDER BY registration_id ASC';

		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$template->assign_block_vars('keys', array(
			));
		}

		$db->sql_freeresult($result);


		$template->assign_vars(array(
				'ERROR'		=> (sizeof($error)) ? implode('<br />', $error) : '',

				'L_TITLE'	=> $user->lang['UCP_TFA'],

				'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
				'S_UCP_ACTION'		=> $this->u_action)
		);

		// Set desired template
		$this->tpl_name = 'ucp_tfa';
		$this->page_title = 'UCP_TFA';
	}
}
