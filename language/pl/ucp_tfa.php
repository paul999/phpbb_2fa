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
		'TFA_NO_KEYS'				=> 'Nie znaleziono kluczy uwierzytelniania dwuskładnikowego. Poniżej możesz dodać klucz.',
		'TFA_KEYS'					=> 'Na tej stronie możesz zarządzać swoimi kluczami uwierzytelniania dwuetapowego. Możesz dodać wiele kluczy do swojego konta. Jeśli zgubisz któryś z kluczy pamiętaj aby go usunąć z listy poniżej!
<br /><br />
Zależnie od konfiguracji wybranej przez administratora forum, może być wymagane podanie klucza bezpieczeństwa przed wejściem na forum.<br /><br />
Niektóre klucze bezpieczeństwa (takie jak te w standardzie U2F) obecnie działają tylko w niektórych przeglądarkach. Z tego też powodu istnieje możliwość, że do Twojego konta są przypisane klucze, jednak dostęp do forum jest zablokowany ponieważ nie znaleziono prawidłowych kluczy działających w Twojej przeglądarce. Sugerujemy przynajmniej zarejestrować jakieś zapasowe klucze i przechowywać je w bezpiecznym miejscu.',
		'TFA_NO_MODE'				=> 'Brak ustawienia', // do przetlumaczenia
		'TFA_KEYS_DELETED'			=> 'Usunięto wybrane klucze.',
		'TFA_NEW'                   		=> 'Dodaj nowy klucz',
		'TFA_ERROR'				=> 'Coś chyba poszło nie tak...',
		'TFA_REG_FAILED'			=> 'Rejestracja zakończona niepowodzeniem z następującym komunikatem błędu: ',
		'TFA_REG_EXISTS'			=> 'Podany klucz został już przypisany do Twojego konta',
		'TFA_ADD_KEY'				=> 'Zarejestruj nowy klucz',
		'TFA_KEY_ADDED'				=> 'Twój klucz bezpieczeństwa został dodany i jest gotowy do użycia.',
		'TFA_INSERT_KEY'			=> 'Podaj swój klucz bezpieczeństwa i naciśnij przycisk na kluczu',
		'TFA_REGISTERED'			=> 'Klucz zarejestrowany',
		'TFA_LAST_USED'				=> 'Ostatnio używany klucz',
		'TFA_MODULE_NOT_FOUND'			=> 'Wybrany moduł (%s) nie został znaleziony',
		'TFA_MODULE_NO_REGISTER'		=> 'Wybrany moduł nie przyjmuje przypisywania do tego konta nowych kluczy',
		'TFA_SELECT_NEW'			=> 'Typ nowego klucza',
		'TFA_ADD_NEW_U2F_KEY'			=> 'Dodaj nowy klucz U2F do swojego konta',
		'TFA_ADD_NEW_OTP_KEY'			=> 'Dodaj nowy klucz OTP do swojego konta',
		'TFA_ADD_OTP_KEY_EXPLAIN'		=> 'Zeskanuj poniższy kod QR aplikacją uwierzytelniającą (np. Google Authenticator), 
		lub wpisz w aplikacji następujący kod bezpieczeństwa: <mark>%s</mark>. Po wykonaniu tej operacji, potwierdź poprzez podanie klucza z aplikacji uwerzytelniającej.',
		'TFA_OTP_KEY'				=> 'Klucz OTP',
		'TFA_OTP_INVALID_KEY'			=> 'Wpisano nieprawidłowy klucz.',
		'TFA_KEYTYPE'				=> 'Typ klucza',
		'TFA_KEY_NOT_USED'			=> 'Jeszcze nie używany',
		'TFA_KEY'				=> 'Klucz zapasowy',
		'TFA_BACKUP_KEY_EXPLAIN'		=> 'Poniżej znajdziesz klucze zapasowe, wygenerowane na wypadek gdybyś zgubił swoje klucze lub gdyby Twój klucz nie działał. Upewnij się, że przechowujesz te klucze w bezpiecznej lokalizacji.<br />
Ogólnie rzecz ujmując, powinieneś wykorzystywać klucze zapasowe tylko w sytuacjach awaryjnych, gdy nie ma innej możliwości.<br /><br />
Po wykorzystaniu wszystkich kluczy, możesz wygenerować nowe.',
	)
);
