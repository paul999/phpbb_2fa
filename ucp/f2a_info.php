<?php
/**
 *
 * 2FA extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 Paul Sohier
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\f2a\ucp;

class f2a_info
{
	public function module()
	{
		return array(
			'filename'	=> '\paul999\f2a\ucp\f2a_module',
			'title'		=> 'UCP_F2A_MANAGE',
			'modes'		=> array(
				'manage'	=> array(
					'title'	=> 'ACP_F2A_MANAGE',
					'auth'	=> 'ext_paul999/f2a',
					'cat'	=> array('UCP_F2A')),
			),
		);
	}
}
