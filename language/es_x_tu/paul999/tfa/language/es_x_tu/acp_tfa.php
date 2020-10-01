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
		'ACP_TFA_SETTINGS'			=> 'Ajustes de Autenticación de dos factores',

		// As we are re-using the acp_board template, we can't add custom stuff to that page.
		// As such, we do need to have some HTML here :(.
		'ACP_TFA_SETTINGS_EXPLAIN'	=> 'Aquí puedes establecer la configuración de dos factores.
										La opción de configuración sugerida para el requisito es no requerir autenticación de dos factores,
										o solo requerir para el inicio de sesión de PCA. <br /><br />
										Existen algunos requisitos del navegador para la clave de seguridad U2F:
										<ul>
											<li>Google Chrome (Al menos versión 41)</li>
										</ul>
										No soportado:
										<ul>
											<li>Internet Explorer</li>
											<li>Edge</li>
											<li>Firefox</li>
											<li>Safari</li>
										</ul>
										<p>Sin embargo, varios proveedores de navegadores prometieron que podría ser compatible con una versión más reciente.
										Cuando un navegador no cumple con estos requisitos, el usuario no podrá seleccionar U2F.</p>
										
										<h2>Recibir soporte</h2>
										<p>El soporte solo se proporciona en www.phpbb.com, en la extensión <a href="https://www.phpbb.com/customise/db/extension/phpbb_two_factor_authentication/" target="_blank">CDB</a>. Asegúrate de leer las FAQ antes de hacer tus preguntas.</p>
										
										<h2>¿Quieres apoyar el desarrollo de esta extensión?</h2>
										<p>Esta extensión se desarrolla al completo en mi tiempo libre, sin embargo, puedes ayudarme proporcionando una pequeña donación para que esta extensión se desarrolle.</p>
										<ul>
											<li>Conviértete en un patrocinador en GitHub: <a href="https://github.com/sponsors/paul999" target="_blank">https://github.com/sponsors/paul999</a></li>
											<li>Haz una donación con PayPal: <a href="https://paypal.me/sohier" target="_blank">https://paypal.me/sohier</a></li>
											<li>Haz una donación a través de Bunq: <a href="https://bunq.me/Paul999" target="_blank">https://bunq.me/Paul999</a></li>
										</ul>
										',
		'TFA_REQUIRES_SSL'			=> 'Parece que estás utilizando una conexión no segura. Esta extensión requiere una conexión SSL segura para que funcionen algunas claves de seguridad. Los usuarios no podrán elegir estas opciones a menos que habilites una conexión segura a tu foro.',

		'TFA_MODE'						=> 'Modo de Autenticación de dos factores',
		'TFA_MODE_EXPLAIN'				=> 'Aquí puedes seleccionar qué usuarios son necesarios (si los hay) para usar el modo de autenticación de dos factores. Si seleccionas "Autenticación de dos factores deshabilitada", se desactivará la funcionalidad por completo.',
		'TFA_DISABLED'					=> 'Autenticación de dos factores deshabilitada',
		'TFA_NOT_REQUIRED'				=> 'No requiere autenticación de dos factores',
		'TFA_REQUIRED_FOR_ACP_LOGIN'	=> 'Requiere autenticación de dos factores solo para el inicio de sesión de PCA',
		'TFA_REQUIRED_FOR_ADMIN'		=> 'Requiere autenticación de dos factores para todos los Administradores',
		'TFA_REQUIRED_FOR_MODERATOR'	=> 'Requerir autenticación de dos factores para todos los Moderadores y Administradores',
		'TFA_REQUIRED'					=> 'Requiere autenticación de dos factores para todos los usuarios',

		'TFA_ACP'           => 'Requiere autenticación de dos factores para el Panel de Administración',
		'TFA_ACP_EXPLAIN'   => 'Cuando se establece en no, los Administradores no necesitan usar una clave de autenticación de dos factores al iniciar sesión para el PCA. Es posible que no se sugiera deshabilitar esto.'
	)
);
