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
		'TFA_REQUIRED_KEY_MISSING'      => 'El administrador de este foro requiere que añadas la identificación en dos pasos para acceder a algunas partes de este foro. Parece que no tienes configurada ninguna. Puedes configurar una nueva clave de seguridad %s aquí%s.<br />Por razones de seguridad este foro ha sido desactivado hasta que añadas la clave de seguridad a tu cuenta. Es probable que en el proceso de añadir esta nueva clave se te requiera tu contraseña de usuario.',
		// Controller
		'ERR_NO_MATCHING_REQUEST'       => 'No se encontró la solicitud',
		'ERR_NO_MATCHING_REGISTRATION'  => 'No se encontró el registro',
		'ERR_AUTHENTICATION_FAILURE'    => 'Error de autentificación',
		'ERR_UNMATCHED_CHALLENGE'       => 'El reto de registro no coincide',
		'ERR_ATTESTATION_SIGNATURE'     => 'La firma de certificación no coincide',
		'ERR_ATTESTATION_VERIFICATION'  => 'El certificado no ha podido ser validado',
		'ERR_BAD_RANDOM'                => 'No se ha podido encontrar una buena fuente de aleatoriedad',
		'ERR_COUNTER_TOO_LOW'           => 'Contador demasiado bajo',
		'ERR_PUBKEY_DECODE'             => 'Error al decodificar la clave pública',
		'ERR_BAD_UA_RETURNING'          => 'Error en el User-Agent',
		'ERR_OLD_OPENSSL'               => 'La versión mínima de OpenSSL debe ser 1.0.0, actualmente tienes instalada %s',
		'UNKNOWN_ERROR'                 => 'Un error desconocido sucedió mientras validábamos tu clave de seguridad. Inténtalo de nuevo más tarde.',

		'ERR_TFA_NO_REQUEST_FOUND_IN_SESSION'	=> 'No se ha encontrado ninguna solicitud en esta sesión. ¿La enviaste utilizando otra página?',
		'TFA_TFA_NOT_REGISTERED'				=> 'La llave de seguridad proporcionada no está registrada en tu cuenta.',

		'FTA_NO_RESPONSE'                   => 'No se ha recibido respuesta',
		'TFA_SELECT_KEY'                    => 'Selecciona el tipo de clave',
		'FTA_NO_RESPONSE_RECEIVED'          => 'We did not receive a response from your U2F security key. Did you press the button?',
		'FTA_NOT_SUPPORTED'                 => 'Navegador no soportado',
		'FTA_BROWSER_SEEMS_NOT_SUPPORTED'   => 'Lo sentimos, esta característica sólo funciona en Google Chrome.',
		'FTA_INSERT_KEY'                    => 'Inserta tu llave de seguridad',
		'FTA_INSERT_KEY_EXPLAIN'            => 'Inserta la llave de seguridad y pulsa el botón.',
		'TFA_NO_ACCESS'						=> 'Parece que no tienes acceso a esta página',
		'TFA_UNABLE_TO_UPDATE_SESSION'		=> 'Imposible renovar la sesión. Contacta con el administrador del foro.',
		'TFA_DISABLED'						=> 'La autenticación en dos paso ha sido desactivada',

		'TFA_OTP_KEY_LOG'					=> 'Clave OTP',
		'TFA_OTP_KEY_LOG_EXPLAIN'			=> 'Abra la app de autenticación e introduzca la clave a continuación',
		'TFA_INCORRECT_KEY'					=> 'La clave suministrada es incorrecta.',
		'TFA_NO_KEY_PROVIDED'				=> 'No se ha suministrado ninguna clave',
		'TFA_KEY_REQUIRED'					=> 'Por favor introduce tu clave de seguridad',

		'TFA_BACKUP_KEY'			=> 'Claves de respaldo',
		'TFA_OTP'					=> 'OTP',
		'TFA_U2F'					=> 'U2F',

		'TFA_BACKUP_KEY_LOG'				=> 'Clave de respaldo',
		'TFA_BACKUP_KEY_LOG_EXPLAIN'		=> 'Proporciona una clave de respaldo que no hayas utilizado antes.',

		'TFA_DOUBLE_PRIORITY'				=> 'La prioridad seleccionada (%d) para el módulo %s ya está en uso para el módulo %s',

		'TFA_SOMETHING_WENT_WRONG'			=> 'Algo fue mal durante la solicitud. Inténtalo de nuevo más tarde.',

		// Module names
		'MODULE_U2F'        => 'U2F',

	)
);
