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
		'TFA_NO_KEYS'				=> 'No se ha encontrado ninguna configuración de autenticación en dos pasos. Puedes añadir una desde el menú que hay a continuación.',
		'TFA_KEYS'					=> 'En esta página puedes gestionar la autenticación en dos pasos.
										Puedes añadir múltiples claves o llaves U2F a tu cuenta.
										Si pierdes tus claves o tus llaves U2F, asegúrate de eliminarlas de tu cuenta.
										<br /><br />
										Dependiendo de la configuración elegida por el administrador del foro, es posible
										que necesites configurar la autenticación en dos pasos antes de acceder al foro.
										<br /><br />
										Algunas llaves de seguridad (como las U2F estándar) sólo funcionan en algunos
										navegadores. Es por esto por lo que es probable que haya algunas llaves registradas
										en tu cuenta pero se te bloquee el acceso al foro. Esto es porque el navegador no
										la está detectando. 
										<br /><br />
										<b>Recomendamos también que registre al menos una clave de respaldo
										y la almacenes en algún sitio seguro.</b>',
		'TFA_NO_MODE'				=> 'Modo  no seleccionado',
		'TFA_KEYS_DELETED'			=> 'Eliminadas las llaves seleccionadas.',
		'TFA_NEW'                   => 'Añadir',
		'TFA_ERROR'					=> 'Parece que algo falló...',
		'TFA_REG_FAILED'			=> 'El registro falló con el siguiente error: ',
		'TFA_REG_EXISTS'			=> 'La llave ya existe en esta cuenta',
		'TFA_ADD_KEY'				=> 'Registrar nueva llave',
		'TFA_KEY_ADDED'				=> 'Tu llave ha sido añadida y puede ser usada.',
		'TFA_INSERT_KEY'			=> 'Conecta tu llave de seguridad y pulsa el botón',
		'TFA_REGISTERED'			=> 'Llave registrada',
		'TFA_LAST_USED'				=> 'Última llave usada',
		'TFA_MODULE_NOT_FOUND'		=> 'El módulo seleccionado (%s) no ha sido encontrado',
		'TFA_MODULE_NO_REGISTER'	=> 'El módulo seleccionado no acepta nuevas llaves',
		'TFA_SELECT_NEW'			=> 'Añadir nueva',
		'TFA_ADD_NEW_U2F_KEY'		=> 'Añadir llave U2F a tu cuenta',
		'TFA_ADD_NEW_OTP_KEY'		=> 'Añadir clave de un sólo uso a tu cuenta',
		'TFA_ADD_OTP_KEY_EXPLAIN'	=> 'Escanea el siguiente código con tu app de autenticación (como Google Authenticator), 
		o introduce el siguiente secreto en la app: %s. A continuación introduce la clave de un sólo uso facilitada por la app.',
		'TFA_OTP_KEY'				=> 'Clave de un sólo uso',
		'TFA_OTP_INVALID_KEY'		=> 'Clave inválida.',
		'TFA_KEYTYPE'				=> 'Tipo de clave',
		'TFA_KEY_NOT_USED'			=> 'No usada aún',
		'TFA_KEY'                   => 'Clave de respaldo',
		'TFA_BACKUP_KEY_EXPLAIN'	=> 'A continuación tienes tus claves de respaldo, generadas en caso de que pierdas tus claves o tu llave no funcione.
		Por favor asegúrate de guardarlas en un lugar seguro.<br />
		Normalmente sólo necesitarías estas claves como último recurso.<br /><br />
		Cuando todas hayan sido usadas, puedes generar unas nuevas.',
	)
);
