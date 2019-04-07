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
		'ACP_TFA_SETTINGS'			=> 'Ustawienia uwierzytelniania dwuskładnikowego',
		'ACP_TFA_SETTINGS_EXPLAIN'	=> 'Tutaj możesz zmienić konfigurację uwierzytelniania dwuskładnikowego.
										Sugerujemy, aby ustawić uwierzytelnianie dwuskładnikowe jako opcjonalne, lub jako wymagane tylko przy uzyskiwaniu dostępu do panelu administracyjnego.<br /><br />
										Istnieją pewne wymagania co do przeglądarek, niezbędne do obsługi kluczy U2F:
										<ul>
											<li>Google Chrome (co najmniej w wersji 41)</li>
										</ul>
										Nie są obsługiwane następujące przeglądarki:
										<ul>
											<li>Internet Explorer</li>
											<li>Edge</li>
											<li>Firefox</li>
											<li>Safari</li>
										</ul>
										Aczkolwiek twórcy niektórych przeglądarek obiecali, że obsługa tych kluczy może się pojawić w którejś z nowych wersji.
										Jeśli używana przeglądarka nie spełnia wymagań, użytkownik nie będzie mógł wybrać U2F.',
		'TFA_REQUIRES_SSL'			=> 'Wygląda na to, że Twoje połączenie nie jest zabezpieczone. Dla niektórych kluczy wymagane jest połączenie szyfrowane przy użyciu SSL.',

		'TFA_MODE'						=> 'Ustawienie uwierzytelniania dwuskładnikowego',
		'TFA_MODE_EXPLAIN'				=> 'Tutaj możesz zdecydować, wobec których użytkowników (jeśli w ogóle) wymagane będzie uwierzytelnianie dwuskładnikowe. Wybranie opcji "Wyłącz uwierzytelnianie dwuskładnikowe" spowoduje całkowite wyłączenie tej funkcjonalności.',
		'TFA_DISABLED'					=> 'Wyłącz uwierzytelnianie dwuskładnikowe',
		'TFA_NOT_REQUIRED'				=> 'Nie wymagaj uwierzytelniania dwuskładnikowego',
		'TFA_REQUIRED_FOR_ACP_LOGIN'	=> 'Wymagaj u. d. tylko przy logowaniu do panelu admin.',
		'TFA_REQUIRED_FOR_ADMIN'		=> 'Wymagaj u. d. dla administratorów',
		'TFA_REQUIRED_FOR_MODERATOR'	=> 'Wymagaj u. d. dla administratorów i moderatorów',
		'TFA_REQUIRED'					=> 'Wymagaj u. d. dla wszystkich użytkowników',
	)
);
