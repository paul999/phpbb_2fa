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
		'TFA_REQUIRED_KEY_MISSING'      => 'Администратор форума требует, чтобы вы добавили ключи двухфакторной аутентификации для доступа к разделам форума,
        поскольку сейчас у вас нет (совместимых) ключей в  вашей учётной записи. Вы можете добавить новые ключи  %s здесь%s.
				<br />В целях безопасности, форум будет недоступен до тех пор, пока вы не добавить ключи к вашей учётной записи.
				Вам может потребоваться ввести ваш пароль при добавлении ключа!
												<br />Обратите внимание, что вы также выйдете из системы.',

		'TFA_REQUIRED_KEY_AVAILABLE_BUT_UNUSABLE' => 'Администратор форума требует, чтобы вы добавили ключи двухфакторной аутентификации для доступа к разделам форума.
		У вас есть требуемые ключи, однако они несовместимы с вашим браузером, настройками или по иной причине. 
														<br />В целях безопасности, мы не позволяем пользователям с уже зарегистрированными ключами добавлять новые ключи, пока они не аутентифицируются полностью . Попробуйте использовать тот браузер, который работал раньше, или 
														же свяжитесь через %s с администратором форума %s с просьбой сбросить настройки двухфакторной аутентификации.',
		// Controller
		'ERR_NO_MATCHING_REQUEST'       => 'Подходящий запрос не найден',
		'ERR_NO_MATCHING_REGISTRATION'  => 'Нет подходящей регистрации',
		'ERR_AUTHENTICATION_FAILURE'    => 'Ошибка аутентификации',
		'ERR_UNMATCHED_CHALLENGE'       => 'Рагистрационный отклик (challenge) не совпадает',
		'ERR_ATTESTATION_SIGNATURE'     => 'Аттестационная подпись не совпадает',
		'ERR_ATTESTATION_VERIFICATION'  => 'Не получилось проверить аттестационный сертификат',
		'ERR_BAD_RANDOM'                => 'Невозможно получить хороший источник случайных чисел',
		'ERR_COUNTER_TOO_LOW'           => 'Слишком малое значение счётчика',
		'ERR_PUBKEY_DECODE'             => 'Ошибка расшифровки публичного ключа',
		'ERR_BAD_UA_RETURNING'          => 'Пользовательский агент вернул ошибку',
		'ERR_OLD_OPENSSL'               => 'Версия OpenSSL должна быть не ниже 1.0.0, текущая %s',
		'UNKNOWN_ERROR'                 => 'Произошла неизвестная ошибка при проверке ключа безопасности. Попробуйте позже.',

		'ERR_TFA_NO_REQUEST_FOUND_IN_SESSION'	=> 'Не найден запрос в тенкущей сессии. Логин с другой страницы ?',
		'TFA_NOT_REGISTERED'				=> 'Используемый ключ не зарегистрирован в вашей учётной записи',

		'FTA_NO_RESPONSE'                   => 'Ответ не принят',
		'TFA_SELECT_KEY'                    => 'Выберите тип ключа',
		'TFA_NO_RESPONSE_RECEIVED'          => 'Мы не приняли ответ от вашего U2F-ключа. Кнопку не забыли нажать ?',
		'TFA_NOT_SUPPORTED'                 => 'Браузер не поддерживается',
		'TFA_BROWSER_SEEMS_NOT_SUPPORTED'   => 'Извините, пока поддерживается только Google Chrome.',
		'TFA_INSERT_KEY'                    => 'Вставьте ваш ключ безопасности',
		'TFA_INSERT_KEY_EXPLAIN'            => 'Вставьте ваш ключ безопасности в компьютер и нажмите “Ключ вставлен”.',
		'TFA_START_AUTH'                    => 'Ключ вставлен',
		'TFA_NO_ACCESS'						=> 'Похоже, у вас нет доступа к данной странице.',
		'TFA_UNABLE_TO_UPDATE_SESSION'		=> 'Ошибка обновления сессии. Сообщите администратору форума.',
		'TFA_DISABLED'						=> 'Двухфакторная аутентификация выключена',

		'TFA_OTP_KEY_LOG'					=> 'OTP-ключ',
		'TFA_OTP_KEY_LOG_EXPLAIN'			=> 'Откройте приложение-аутентификатор и введите сгенерированный код в нижележащее поле',
		'TFA_INCORRECT_KEY'					=> 'Ключ не корректен.',
		'TFA_NO_KEY_PROVIDED'				=> 'Ключ не предоставлен',
		'TFA_KEY_REQUIRED'					=> 'Требуется ключ безопасности',

		'TFA_BACKUP_KEY'			=> 'Резервные ключи',
		'TFA_OTP'					=> 'OTP',
		'TFA_U2F'					=> 'U2F',

		'TFA_CP_TXT'				=> 'phpBB Двухфакторная аутентификация',
		'TFA_CP_NAME'				=> 'paul999',

		'TFA_BACKUP_KEY_LOG'				=> 'Резервный ключ',
		'TFA_BACKUP_KEY_LOG_EXPLAIN'		=> 'Введите резервный ключ, который ранее не использовался.',

		'TFA_DOUBLE_PRIORITY'				=> 'Выбранный приоритет (%d) для модуля %s уже используется в модуле %s',

		'TFA_SOMETHING_WENT_WRONG'			=> 'При обработке запроса что-то пошло не так. Попробуйте позже.',

		// Module names
		'MODULE_U2F'        => 'U2F',

	)
);
