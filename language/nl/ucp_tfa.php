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
		'TFA_NO_KEYS'				=> 'Geen tweefactorauthenticatiesleutels gevonden. Hieronder kun je er een toevoegen.',
		'TFA_KEYS'					=> 'Op deze pagina kunt u uw tweefactorauthenticatiesleutels beheren.
                                        U kunt meerdere sleutels aan uw account toevoegen.
											Als je sleutels verliest of verbruikt, zorg er dan voor dat u ze van de account verwijdert!
										<br /><br />
										Afhankelijk van de gekozen configuratie gekozen door de forumbeheerder,
                                        kan zijn dat je een beveiligingssleutel moet toevoegen, voordat je toegang krijgt tot het forum.
										<br /><br />
										Sommige beveiligingssleutels (zoals de U2F-standaard) werken momenteel alleen in specifieke
                                        browsers. Hierdoor is het mogelijk dat er sleutels zijn geregistreerd op je account, 
                                        maar de toegang tot het forum is geblokkeerd, omdat er geen geldige sleutels zijn gevonden
                                        die werken met uw browser. Er wordt voorgesteld om op zijn minst enkele back-upsleutels te 
                                        registreren en bewaar ze op een veilige plaats.',
		'TFA_NO_MODE'				=> 'Nee modes',
		'TFA_KEYS_DELETED'			=> 'Geselecteerde sleutels verwijderd.',
		'TFA_NEW'                   => 'Nieuwe sleutel toevoegen',
		'TFA_ERROR'					=> 'Het lijkt erop dat er iets mis is gegaan...',
		'TFA_REG_FAILED'			=> 'Registratie mislukt met fout: ',
		'TFA_REG_EXISTS'			=> 'De verstrekte sleutel is al geregistreerd op je account',
		'TFA_ADD_KEY'				=> 'Nieuwe sleutel registreren',
		'TFA_KEY_ADDED'				=> 'Uw beveiligingssleutel is toegevoegd en kan worden gebruikt.',
		'TFA_INSERT_KEY'			=> 'Voer je beveiligingssleutel in en druk op de knop op de sleutel',
		'TFA_REGISTERED'			=> 'Sleutel geregistreerd',
		'TFA_LAST_USED'				=> 'Sleutel laatst gebruikt',
		'TFA_MODULE_NOT_FOUND'		=> 'De geselecteerde module (%s) is niet gevonden',
		'TFA_MODULE_NO_REGISTER'	=> 'De geselecteerde module accepteert geen nieuwe sleutels voor registratie',
		'TFA_SELECT_NEW'			=> 'Nieuwe sleutel toevoegen',
		'TFA_ADD_NEW_U2F_KEY'		=> 'Voeg een nieuwe U2F-sleutel toe aan uw account',
		'TFA_ADD_NEW_OTP_KEY'		=> 'Voeg een nieuwe OTP-sleutel toe aan uw account',
		'TFA_ADD_OTP_KEY_EXPLAIN'	=> 'Scan de onderstaande QR-code met een Authenticator-app (zoals Google Authenticator)
        of vul de volgende geheim in de app in: %s. Bevestig daarna door een sleutel van uw Authenticator-app op te geven.',
		'TFA_OTP_KEY'				=> 'OTP-sleutel',
		'TFA_OTP_INVALID_KEY'		=> 'Ongeldige sleutel opgegeven.',
		'TFA_KEYTYPE'				=> 'Slutel type',
		'TFA_KEY_NOT_USED'			=> 'Nog niet gebruikt',
		'TFA_KEY'                   => 'Backup sleutel',
		'TFA_BACKUP_KEY_EXPLAIN'	=> 'Hieronder vind je back-upsleutels. Bewaar deze sleutels op een veilige plaats. <br />
                                        Gebruik een back-upsleutel alleen als laatste redmiddel. <br /><br />
                                        Wanneer alle sleutels zijn gebruikt kan je nieuwe sleutels genereren. ',
	)
);
