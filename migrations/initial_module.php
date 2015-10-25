<?php
/**
 *
 * 2FA extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 Paul Sohier
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\fta\migrations;

/**
* Migration stage 6: Initial module
*/
class initial_module extends \phpbb\db\migration\migration
{
	/**
	 * Add or update data in the database
	 *
	 * @return array Array of table data
	 * @access public
	 */
	public function update_data()
	{
		return array(
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_F2A')),
			array('module.add', array(
				'acp', 'ACP_F2A_MANAGE', array(
					'module_basename'	=> '\paul999\fta\acp\f2a_module',
					'modes'				=> array('manage'),
				),
			)),
			array('module.add', array('ucp', 'UCP_MAIN', 'UCP_F2A')),
			array('module.add', array(
				'ucp', 'UCP_F2A_MANAGE', array(
					'module_basename'	=> '\paul999\fta\ucp\f2a_module',
					'modes'				=> array('manage'),
				),
			)),
		);
	}
}
