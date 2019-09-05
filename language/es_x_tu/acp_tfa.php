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
		'ACP_TFA_SETTINGS'			=> 'Configuración de autenticación en dos pasos',

		// As we are re-using the acp_board template, we can't add custom stuff to that page.
		// As such, we do need to have some HTML here :(.
		'ACP_TFA_SETTINGS_EXPLAIN'	=> 'Aquí puedes ajustar la configuración de la autenticación en dos pasos.
										La configuración sugerida es la de habilitar la autenticación en dos pasos para el panel
										de control de administración.<br /><br />
										Las llaves U2F sólo son soportadas por el momento por los siguientes navegadores:
										<ul>
											<li>Google Chrome (Versión 41 en adelante)</li>
										</ul>
										No soportado
										<ul>
											<li>Internet Explorer</li>
											<li>Edge</li>
											<li>Firefox</li>
											<li>Safari</li>
										</ul>
										<p>En cualquier caso, es probable que alguno de estos navegadores ya las soporte en versiones más actuales.
										Cuando un navegador no lo soporta, al usuario no le aparecerá la opción de seleccionar U2F</p>

										<h2>Soporte</h2>
										<p>El soporte sólo se proporcionará en www.phpbb.com, en el <a href="https://www.phpbb.com/community/viewtopic.php?f=456&t=2341856" target="_blank">hilo de esta extensión.</a> Por favor asegúrate de leer
										el primer mensaje antes de realizar una pregunta.</p>

										<h2>Atención: Esta es una extensión en desarrollo</h2>
										<p>Esta extensión está en desarrollo. Esta extensión está pensada en añadir más características de seguridad a
										tu foro. Por favor toma toda las precauciones antes de instalarla en un foro en producción.</p>
										',
		'TFA_REQUIRES_SSL'			=> 'Parece que estás usando una conexión insegura. Esta extensión requiere una conexión TLS/SSL segura para que algunas llaves de seguridad funcionen. Esta opción no aparecerá a los usuarios a menos que habilites una conexión segura a tu foro.',

		'TFA_MODE'						=> 'Modo de autenticación en dos pasos',
		'TFA_MODE_EXPLAIN'				=> 'Aquí puedes seleccionar qué usuarios pueden usar la autenticación en dos pasos. Si seleccionas "Autenticación en dos pasos desactivada" esta no estará activa en ningún caso.',
		'TFA_DISABLED'					=> 'Autenticación en dos pasos desactivada',
		'TFA_NOT_REQUIRED'				=> 'Activar pero no requerir autenticación en dos pasos',
		'TFA_REQUIRED_FOR_ACP_LOGIN'	=> 'Requerir autenticación en dos pasos para acceder al panel de Administrador',
		'TFA_REQUIRED_FOR_ADMIN'		=> 'Requerir autenticación en dos pasos a todos los administradores',
		'TFA_REQUIRED_FOR_MODERATOR'	=> 'Requerir autenticación en dos pasos a todos los administradores y moderadores',
		'TFA_REQUIRED'					=> 'Requerir autenticación en dos pasos a todos los usuarios',
	)
);
