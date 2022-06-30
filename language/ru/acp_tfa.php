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
		'ACP_TFA_SETTINGS'			=> 'Настройки двухфакторной аутентификации',

		// As we are re-using the acp_board template, we can't add custom stuff to that page.
		// As such, we do need to have some HTML here :(.
		'ACP_TFA_SETTINGS_EXPLAIN'	=> 'Здесь вы можете настроить параметры двухфакторной аутентификации.
										Вы можете вообще выключить двухфакторную аутентификацию, или требовать её
										только для доступа к ACP-панели.<br /><br />
										Для U2F-ключей есть требования к браузерам:
										<ul>
											<li>Google Chrome (начиная с 41-й версии)</li>
										</ul>
										Не поддерживаются:
										<ul>
											<li>Internet Explorer</li>
											<li>Edge</li>
											<li>Firefox</li>
											<li>Safari</li>
										</ul>
										<p>Впрочем, производители браузеров могут добавить поддержку U2F в новых релизах.
										Если бразуер не поддерживает U2F, пользователь не сможет выбрать U2F-ключи.</p>

										<h2>Получение поддержки</h2>
										<p>Поддержка оказывается только через сайт www.phpbb.com, на странице расширений <a href="https://www.phpbb.com/customise/db/extension/phpbb_two_factor_authentication/" target="_blank">customisations database</a>.
										Убедитесь, пожалуйста, что вы прочитали FAQ до того, как задать вопрос.</p>

										<h2>Хотите помочь с разработкой данного расширения ?</h2>
										<p>Это расширение разрабатывается исключительно в моё свободное время, и вы можете поддержать разработку данного расширения небольшим донатом.</p>
										<ul>
											<li>Стать спонсором на GitHub: <a href="https://github.com/sponsors/paul999" target="_blank">https://github.com/sponsors/paul999</a></li>
											<li>Пожертвовать через PayPal: <a href="https://paypal.me/sohier" target="_blank">https://paypal.me/sohier</a></li>
											<li>Пожертвовать через bunq: <a href="https://bunq.me/Paul999" target="_blank">https://bunq.me/Paul999</a></li>
										</ul>
										',
		'TFA_REQUIRES_SSL'			=> 'Похоже, вы используете нешифрованные соединения. Это расширение требует SSL/TLS соединений, чтобы некоторые типы ключей могли работать. Пользователи не смогут выбрать эти опции, если они не смогут соединиться с форумом по шифрованной связи.',

		'TFA_MODE'						=> 'Режим двухфакторной аутентификации',
		'TFA_MODE_EXPLAIN'				=> 'Здесь можно выбрать, каким пользователем будет требоваться (если вообще требоваться) использовать режим двухфакторной аутентификации. Выберите “Двухфакторная аутентификация выключена” чтобы полностью выключить функции двухфакторной аутентификации.',
		'TFA_DISABLED'					=> '2ФА выключена',
		'TFA_NOT_REQUIRED'				=> '2ФА опциональна (только для логина, индивидуально)',
		'TFA_REQUIRED_FOR_ACP_LOGIN'	=> 'Требуется для доступа к ACP-панели',
		'TFA_REQUIRED_FOR_ADMIN'		=> 'Требуется для всех администраторов',
		'TFA_REQUIRED_FOR_MODERATOR'	=> 'Требуется для всех администраторов и модераторов',
		'TFA_REQUIRED'					=> 'Требуется для всех пользователей',

		'TFA_ACP'           => 'Для панели администрирования обязательна двухфакторная аутентификация',
		'TFA_ACP_EXPLAIN'   => 'Есть поставить "нет", администраторам не потребуется использовать двухфакторную аутентификацию для логина в ACP-панель.
		"Да" - более параноидальный режим защиты ACP.'
	)
);
