<?php
/**
 *
 * 2FA extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 Paul Sohier
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\f2a\acp;

class f2a_info
{
	public function module()
	{
		return array(
			'filename'	=> '\paul999\f2a\acp\f2a_module',
			'title'		=> 'ACP_F2A_MANAGE',
			'modes'		=> array(
				'manage'	=> array(
					'title'	=> 'ACP_F2A_MANAGE',
					'auth'	=> 'ext_paul999/f2a && acl_a_f2a',
					'cat'	=> array('ACP_F2A')),
			),
		);
	}
}
