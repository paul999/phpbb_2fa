<?php
/**
 *
 * 2FA extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 Paul Sohier
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge(
	$lang, array(
		'ACP_TFA_SETTINGS'			=> 'Two factor authentication settings',
		'ACP_TFA_SETTINGS_EXPLAIN'	=> 'Here you can set the configuration for two factor settings.
										The suggested configuration option for the requirement is or do not require Two factor authentication,
										or only require it for the ACP login. <br /><br />
										The following browsers currently support the technology in used by this extension:
										<ul>
											<li>Google Chrome (At least version 41)</li>
										</ul>
										Not supported:
										<ul>
											<li>Internet Explorer</li>
											<li>Edge</li>
											<li>Firefox</li>
											<li>Safari</li>
										</ul>
										However, several browser vendors promised it might be supported in a newer release.
										We will provide a update to this extension when a browser provides support for it.',
		'TFA_REQUIRES_SSL'			=> 'You seem to be using a non secure connection. This extension requires a secure SSL connection to work. Without secure connection, it won’t function properly!',
	)
);