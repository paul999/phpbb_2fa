<?php
/**Compatible* DO NOT CHANGE
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
		'TFA_REQUIRED_KEY_MISSING'      => 'Administrator tego forum wymaga, abyś dodał klucz Uwierzytelniania Dwuskładnikowego aby uzyskać dostęp do (pewnych) części tego forum, jednak do Twojego konta nie są przypisane żadne (odpowiednie) klucze. Nowy klucz bezpieczeństwa możesz dodać %s tutaj%s
		<br />Ze względów bezpieczeństwa, dostęp do forum został dla Ciebie zablokowany do momentu aż dodasz klucz bezpieczeństwa do swojego konta. Podczas dodawania klucza może być wymagane podanie hasła!',
		// Controller
		'TFA_SOMETHING_WENT_WRONG'      => 'Stało się coś nieprzewidzianego. Spróbuj ponownie później',
		'ERR_NO_MATCHING_REQUEST'       => 'Nie znaleziono pasującego żądania',
		'ERR_NO_MATCHING_REGISTRATION'  => 'Nie znaleziono pasującego przypisania',
		'ERR_AUTHENTICATION_FAILURE'    => 'Uwierzytelnianie nie powiodło się',
		'ERR_UNMATCHED_CHALLENGE'       => 'Niezgodne zadanie przypisania',
		'ERR_ATTESTATION_SIGNATURE'     => 'Niezgodna sygnatura poświadczenia',
		'ERR_ATTESTATION_VERIFICATION'  => 'Nie udało się zweryfikować certyfikatu poświadczenia',
		'ERR_BAD_RANDOM'                => 'Nie udało się zdobyć odpowiedniego źródła losowości',
		'ERR_COUNTER_TOO_LOW'           => 'Licznik zbyt niski',
		'ERR_PUBKEY_DECODE'             => 'Nie udało się odkodować klucza publicznego',
		'ERR_BAD_UA_RETURNING'          => 'User-agent zwrócił błąd',
		'ERR_OLD_OPENSSL'               => 'OpenSSL musi być co najmniej w wersji 1.0.0, obecna to %s',
		'UNKNOWN_ERROR'                 => 'Podczas sprawdzania Twojego klucza bezpieczeństwa wystąpił nieznany błąd. Spróbuj ponownie później.',

		'ERR_TFA_NO_REQUEST_FOUND_IN_SESSION'	=> 'W obecnej sesji nie znaleziono żądania. Czy wysłałeś je za pośrednictwem innej strony?',
		'TFA_TFA_NOT_REGISTERED'				=> 'Wykorzystany klucz bezpieczeństwa nie został przypisany do Twojego konta.',

		'FTA_NO_RESPONSE'                   => 'Nie otrzymano odpowiedzi',
		'FTA_NO_RESPONSE_RECEIVED'          => 'Nie otrzymaliśmy odpowiedzi od Twojego klucza bezpieczeństwa U2F. Czy kliknąłeś przycisk?',
		'FTA_NOT_SUPPORTED'                 => 'niewspierana przeglądarka',
		'FTA_BROWSER_SEEMS_NOT_SUPPORTED'   => 'Przepraszamy, obecnie obsługiwana jest tylko przeglądarka Google Chrome.',
		'FTA_INSERT_KEY'                    => 'Wprowadź klucz bezpieczeństwa',
		'FTA_INSERT_KEY_EXPLAIN'            => 'Wprowadź klucz bezpieczeństwa na komputerze i kliknij przycisk na kluczu.',
		'TFA_NO_ACCESS'						=> 'Chyba nie masz dostępu do tej strony?',
		'TFA_UNABLE_TO_UPDATE_SESSION'		=> 'Nie udało się zaktualizować sesji. Skontaktuj się z administratorem forum',
		'TFA_DISABLED'						=> 'Wyłączono uwierzytelnianie dwuskładnikowe',

		'TFA_OTP_KEY_LOG'					=> 'Klucz OTP',
		'TFA_OTP_KEY_LOG_EXPLAIN'			=> 'Otwórz aplikację uwierzytelniającą i wprowadź tutaj wyświetlony przez nią kod',
		'TFA_INCORRECT_KEY'					=> 'Wprowadzony klucz był nieprawidłowy.',
		'TFA_NO_KEY_PROVIDED'				=> 'Nie wprowadzono klucza',
		'TFA_KEY_REQUIRED'					=> 'Wprowadź swój klucz bezpieczeństwa',

		'TFA_BACKUP_KEY'			=> 'Zapasowe klucze',
		'TFA_OTP'					=> 'OTP',
		'TFA_U2F'					=> 'U2F',

		'TFA_BACKUP_KEY_LOG'				=> 'Zapasowy klucz',
		'TFA_BACKUP_KEY_LOG_EXPLAIN'		=> 'Wprowadź zapasowy klucz, którego jeszcze nie używałeś.',

		'TFA_DOUBLE_PRIORITY'				=> 'Wybrany priorytet (%d) dla modułu %s jest już wykorzystywany w module %s',

		// Module names
		'MODULE_U2F'        => 'U2F',

	)
);
