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
		'TFA_REQUIRED_KEY_MISSING'      => 'De beheerder van dit forum vereist dat je een tweefactorauthenticatiesleutel gebruikt om toegang te krijgen tot (delen) van dit forum. Je hebt momenteel geen beveiligingssleutels gekoppeld op je account. Je kunt %s hier %s nieuwe beveiligingssleutels toevoegen.
												<br />Om veiligheidsredenen is het forum uitgeschakeld totdat je een beveiligingssleutel aan je account hebt toegevoegd.
												<br />Houd er rekening mee dat je nu ook wordt uitgelogd.',

		'TFA_REQUIRED_KEY_AVAILABLE_BUT_UNUSABLE' => 'De beheerder van dit forum vereist dat je een tweefactorauthenticatiesleutel gebruikt om toegang te krijgen tot (delen) van dit forum. Je hebt tweefactorauthenticatiesleutels geregistreerd, maar deze zijn momenteel niet (compatibel) met je browser of zijn anderszins niet beschikbaar. 
														<br />Om veiligheidsredenen staan we gebruikers met reeds geregistreerde sleutels niet toe om een nieuwe toe te voegen zonder volledig ingelogd te zijn. Je kunt proberen in te loggen met een browser die eerder werkte of neem contact op met de %s beheerder %s om een reset aan te vragen van je tweefactorauthenticatiesleutels.',
		// Controller
		'ERR_NO_MATCHING_REQUEST'       => 'Geen overeenkomend verzoek gevonden',
		'ERR_NO_MATCHING_REGISTRATION'  => 'Geen overeenkomende registratie gevonden',
		'ERR_AUTHENTICATION_FAILURE'    => 'Verificatie mislukt',
		'ERR_UNMATCHED_CHALLENGE'       => 'Registratie komt niet overeen',
		'ERR_ATTESTATION_SIGNATURE'     => 'Handtekening komt niet overeen',
		'ERR_ATTESTATION_VERIFICATION'  => 'Certificaat kan niet worden gevalideerd',
		'ERR_BAD_RANDOM'                => 'Geen goede bron kunnen vinden',
		'ERR_COUNTER_TOO_LOW'           => 'Teller te laag',
		'ERR_PUBKEY_DECODE'             => 'Decodering van de openbare sleutel mislukt',
		'ERR_BAD_UA_RETURNING'          => 'User-agent geretourneerde fout',
		'ERR_OLD_OPENSSL'               => 'OpenSSL moet minimaal versie 1.0.0 zijn, dit is %s',
		'UNKNOWN_ERROR'                 => 'Er is een onbekende fout opgetreden tijdens de validatie van de beveiligingssleutel. Probeer het later opnieuw.',

		'ERR_TFA_NO_REQUEST_FOUND_IN_SESSION'	=> 'Geen verzoek gevonden in de huidige sessie. Heb je dit via een andere pagina ingediend?',
		'TFA_NOT_REGISTERED'				=> 'De gebruikte beveiligingssleutel is niet geregistreerd op je account',

		'FTA_NO_RESPONSE'                   => 'Geen reactie ontvangen',
		'TFA_SELECT_KEY'                    => 'Kies sleuteltype',
		'TFA_NO_RESPONSE_RECEIVED'          => 'We hebben geen reactie ontvangen van de U2F-beveiligingssleutel. Heb je op de knop gedrukt?',
		'TFA_NOT_SUPPORTED'                 => 'Browser niet ondersteund',
		'TFA_BROWSER_SEEMS_NOT_SUPPORTED'   => 'Sorry, momenteel wordt alleen Google Chrome ondersteund.',
		'TFA_INSERT_KEY'                    => 'Voer uw beveiligingssleutel in',
		'TFA_INSERT_KEY_EXPLAIN'            => 'Plaats uw beveiligingssleutel in uw computer en klik op "sleutel geplaatst".',
		'TFA_START_AUTH'                    => 'Sleutel geplaatst',
		'TFA_NO_ACCESS'						=> 'Het lijkt erop dat u geen toegang heeft tot deze pagina?',
		'TFA_UNABLE_TO_UPDATE_SESSION'		=> 'Kan sessie niet updaten. Neem contact op met de beheerder',
		'TFA_DISABLED'						=> 'Tweefactorauthenticatie is uitgeschakeld',

		'TFA_OTP_KEY_LOG'					=> 'OTP sleutel',
		'TFA_OTP_KEY_LOG_EXPLAIN'			=> 'Open de authenticator-app en neem de huidige weergegeven sleutel over in het onderstaande tekstveld',
		'TFA_INCORRECT_KEY'					=> 'De opgegeven sleutel was niet correct.',
		'TFA_NO_KEY_PROVIDED'				=> 'Geen sleutel meegeleverd',
		'TFA_KEY_REQUIRED'					=> 'Geef de beveiligingssleutel op',

		'TFA_BACKUP_KEY'			=> 'Backup sleutels',
		'TFA_OTP'					=> 'OTP',
		'TFA_U2F'					=> 'U2F',

		'TFA_CP_TXT'				=> 'phpBB Two Factor Authentication',
		'TFA_CP_NAME'				=> 'paul999',

		'TFA_BACKUP_KEY_LOG'				=> 'Backup sleutel',
		'TFA_BACKUP_KEY_LOG_EXPLAIN'		=> 'Geef een back-upsleutel op die nog niet eerder is gebruikt.',

		'TFA_DOUBLE_PRIORITY'				=> 'De gekozen prioriteit (%d) voor module %s is al in gebruik voor module %s',

		'TFA_SOMETHING_WENT_WRONG'			=> 'Er is iets misgegaan tijdens het verzoek. Probeer het later opnieuw',

		// Module names
		'MODULE_U2F'        => 'U2F',

	)
);
