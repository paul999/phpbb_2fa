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
		'TFA_NO_KEYS'				=> 'Не найдены ключи двухфакторной аутентификации. Вы можете их добавить ниже.',
		'TFA_KEYS'					=> 'На этой странице вы можете управлять своими ключами двухфакторной аутентификации.
										Вы можете добавить несколько ключей к своей учётной записи.
										Если вы потеряли какой-либо из ключей, не забудьте его удалить отсюда !
										<br /><br />
										В зависимости от выбранной администратором форума конфигурации,
										может потребоваться добавить ключ перед тем, как получить доступ к форуму.
										<br /><br />
										Некоторые ключи (например, стандарта U2F) пока что работают лишь в некоторых браузерах.
										Из-за этого возможна ситуация, когда ключи добавлены к учётной записи, но доступ на форум
										будет заблокирован, поскольку не удастся найти ключи, работающие в вашем браузере.
										Посему крайне рекомендуется как минимум создать резервные ключи и сохранить
										их в безопасном месте.',
		'TFA_NO_MODE'				=> 'Режим "НЕТ" (ВЫКЛЮЧЕНО)',
		'TFA_KEYS_DELETED'			=> 'Выбранные ключи удалены.',
		'TFA_NEW'                   => 'Добавить новый',
		'TFA_ERROR'					=> 'Что-то пошло не так ...',
		'TFA_REG_FAILED'			=> 'Ошибка регистрации: ',
		'TFA_REG_EXISTS'			=> 'Предоставленный ключ уже был добавлен к вашей учётной записи',
		'TFA_ADD_KEY'				=> 'Добавить ключ',
		'TFA_KEY_ADDED'				=> 'Ваш ключ безопасности был добавлен и может использоваться.',
		'TFA_INSERT_KEY'			=> 'Вставьте ваш ключ и нажмите на нём кнопку',
		'TFA_REGISTERED'			=> 'Ключ зарегистрирован.',
		'TFA_LAST_USED'				=> 'Ключ использован в последний раз',
		'TFA_MODULE_NOT_FOUND'		=> 'Выбранный модуль (%s) не найден',
		'TFA_MODULE_NO_REGISTER'	=> 'Выбранный модуль не принимает новые ключи к регистрации',
		'TFA_SELECT_NEW'			=> 'Выбрать новый ключ',
		'TFA_ADD_NEW_U2F_KEY'		=> 'Добавить новый U2F-ключ к учётной записи',
		'TFA_ADD_NEW_OTP_KEY'		=> 'Добавить новый OTP-ключ к учётной записи',
		'TFA_ADD_OTP_KEY_EXPLAIN'	=> 'Отсканируйте QR-код ниже с помощью TOTP-приложения (например, Google Authenticator),
		или введите вручную в приложение данный секрет: %s. После этого подтвердите добавление ключа вводом сгенерированного кода из приложения.',
		'TFA_OTP_KEY'				=> 'OTP-ключ',
		'TFA_OTP_INVALID_KEY'		=> 'Представлен не корректный ключ.',
		'TFA_KEYTYPE'				=> 'Тип ключа',
		'TFA_KEY_NOT_USED'			=> 'Не использовался',
		'TFA_KEY'                   => 'Резервный ключ',
		'TFA_BACKUP_KEY_EXPLAIN'	=> 'Ниже идут резервные ключи, созданные на случай потери или поломки ваших основных ключей.
                                        Убедитесь, что храните их безопасно.
                                        В целом, вы должны использовать резервные ключи только как средство последнего шанса.
										<br /><br />
										Когда все резервные ключи будут использованы, вы можете сгенерировать новые.',
	)
);
