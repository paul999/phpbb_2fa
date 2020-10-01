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
		'TFA_NO_KEYS'				=> 'No se encontraron claves de autenticación de dos factores. Puede añadir una a continuación.',
		'TFA_KEYS'					=> 'En esta página puede gestionar sus claves de autenticación de dos factores.
										Puede añadir varias claves a su cuenta.
										Si pierde sus claves, asegúrese de eliminarlas de su cuenta.
										<br /><br />
										Dependiendo de la configuración elegida por el administrador del foro, 
										es posible que deba añadir una clave de seguridad antes de acceder al foro.
										<br /><br />
										Algunas llaves de seguridad (como el estándar U2F) actualmente solo funcionan en 
										navegadores. Debido a eso, es posible que haya claves registradas en su 
										cuenta, pero el acceso al foro está bloqueado porque no se encuentran claves válidas 
										que funcionan con su navegador. Se sugiere registrar al menos algunas claves de respaldo 
										y guárdelas en un lugar seguro.',
		'TFA_NO_MODE'				=> 'Sin modo',
		'TFA_KEYS_DELETED'			=> 'Se eliminaron las claves seleccionadas.',
		'TFA_NEW'                   => 'Añadir nueva clave',
		'TFA_ERROR'					=> 'Parece que algo salió mal...',
		'TFA_REG_FAILED'			=> 'El registro falló con error: ',
		'TFA_REG_EXISTS'			=> 'La clave proporcionada ya se ha registrado en su cuenta',
		'TFA_ADD_KEY'				=> 'Registrar nueva clave',
		'TFA_KEY_ADDED'				=> 'Su clave de seguridad ha sido agregada y puede usarse.',
		'TFA_INSERT_KEY'			=> 'Inserte su clave de seguridad y presione el botón en la clave',
		'TFA_REGISTERED'			=> 'Clave registrada',
		'TFA_LAST_USED'				=> 'Última clave utilizada',
		'TFA_MODULE_NOT_FOUND'		=> 'No se ha encontrado el módulo seleccionado (%s)',
		'TFA_MODULE_NO_REGISTER'	=> 'El módulo seleccionado no acepta nuevas claves para el registro',
		'TFA_SELECT_NEW'			=> 'Añadir nueva clave',
		'TFA_ADD_NEW_U2F_KEY'		=> 'Añadir una nueva clave U2F a su cuenta',
		'TFA_ADD_NEW_OTP_KEY'		=> 'Añadir una nueva clave OTP a su cuenta',
		'TFA_ADD_OTP_KEY_EXPLAIN'	=> 'Escanee el código QR a continuación con una aplicación Authenticator (como Google Authenticator), 
		o complete el siguiente secreto en la aplicación: %s. Después de eso, confirme proporcionando una clave de su aplicación de Authenticator.',
		'TFA_OTP_KEY'				=> 'Clave OTP',
		'TFA_OTP_INVALID_KEY'		=> 'Se proporcionó una clave no válida.',
		'TFA_KEYTYPE'				=> 'Tipo de clave',
		'TFA_KEY_NOT_USED'			=> 'Aún no utilizado',
		'TFA_KEY'                   => 'Clave de respaldo',
		'TFA_BACKUP_KEY_EXPLAIN'	=> 'A continuación se muestran las claves de respaldo, generadas en caso de que pierda sus claves o su clave no 
										funcione. Asegúrese de guardar estas claves en un lugar seguro.<br />
										En general, solo debe usar una clave de respaldo como último recurso.<br /><br />
										Cuando se utilizan todas las claves, puede generar nuevas claves.',
	)
);
