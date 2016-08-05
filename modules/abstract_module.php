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

abstract class abstract_module implements module_interface
{
	/**
	 * @var \phpbb\db\driver\driver_interface
	 */
	protected $db;

	/**
	 * @var \phpbb\user
	 */
	protected $user;

	/**
	 * @var \phpbb\template\template
	 */
	protected $template;

	/**
	 * This method is called to show the UCP page.
	 * You can assign template variables to the template, or do anything else here.
	 *
	 * @param string $table
	 */
	protected function show_ucp_complete($table)
	{
		$sql = 'SELECT *
			FROM ' . $table . '
			WHERE user_id = ' . (int) $this->user->data['user_id'] . '
			ORDER BY registration_id ASC';

		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('keys', array(
				'CLASS'         => $this->get_name(),
				'ID'            => $row['registration_id'],
				'REGISTERED'    => $this->user->format_date($row['registered']),
				'LAST_USED'     => $this->user->format_date($row['last_used']),
				'TYPE'			=> $this->user->lang($this->get_translatable_name()),
			));
		}
		$this->db->sql_freeresult($result);
	}

	/**
	 * Check if the provided user as a specific key in the table provided
	 *
	 * @param string $table Table to check in
	 * @param int    $user_id The specific user
	 *
	 * @return bool
	 */
	protected function check_table_for_user($table, $user_id)
	{
		$sql = 'SELECT COUNT(registration_id) as reg_id 
					FROM ' . $table . ' 
					WHERE 
						user_id = ' . (int) $user_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row && $row['reg_id'] > 0;
	}
}