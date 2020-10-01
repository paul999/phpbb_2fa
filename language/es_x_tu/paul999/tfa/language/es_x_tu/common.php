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
		'TFA_REQUIRED_KEY_MISSING'      => 'El Administrador de este foro requiere que hayas añadido una clave de autenticación de dos factores para acceder a partes (limitadas) de este foro, sin embargo, actualmente no tienes claves (compatibles) registradas en tu cuenta. Puedes añadir una nueva clave de seguridad %saquí%s.
												<br />Por razones de seguridad, el foro se ha desactivado hasta que añadas una clave de seguridad a tu cuenta. ¡Es posible que debas ingresar tu contraseña mientras añades una clave de seguridad!
												<br />Ten en cuenta que ahora también se cerrará la sesión.',

		'TFA_REQUIRED_KEY_AVAILABLE_BUT_UNUSABLE' => 'El Administrador de este foro requiere que hayas añadido claves de autenticación de dos factores para tu cuenta a partes (limitadas) de este foro. Has registrado claves de autenticación de dos factores, sin embargo, actualmente no son (compatibles) con tu navegador, configuración actual, o no están disponibles de otra manera. 
														<br />Por razones de seguridad, no permitimos que los usuarios con claves ya registradas agreguen una nueva sin haber iniciado sesión por completo. Puedes intentar iniciar sesión con un navegador que funcionó antes o, de lo contrario, contacta con el %sAdministrador del Foro%s para solicitar reinicios de tus claves de autenticación de dos factores.',
		// Controller
		'ERR_NO_MATCHING_REQUEST'       => 'No se encontró ninguna solicitud coincidente',
		'ERR_NO_MATCHING_REGISTRATION'  => 'No se encontró ningún registro coincidente',
		'ERR_AUTHENTICATION_FAILURE'    => 'Error de autenticación',
		'ERR_UNMATCHED_CHALLENGE'       => 'El desafío de registro no coincide',
		'ERR_ATTESTATION_SIGNATURE'     => 'La firma de atestación no coincide',
		'ERR_ATTESTATION_VERIFICATION'  => 'El certificado de atestación no se puede validar',
		'ERR_BAD_RANDOM'                => 'No se puede obtener una buena fuente de aleatoriedad',
		'ERR_COUNTER_TOO_LOW'           => 'Contador demasiado bajo',
		'ERR_PUBKEY_DECODE'             => 'Falló la decodificación de la clave pública',
		'ERR_BAD_UA_RETURNING'          => 'El usuario-agente devolvió el error',
		'ERR_OLD_OPENSSL'               => 'OpenSSL tiene que ser al menos la versión 1.0.0, esto es %s',
		'UNKNOWN_ERROR'                 => 'Ocurrió un error desconocido durante la validación de su clave de seguridad. Por favor, inténtalo de nuevo más tarde.',

		'ERR_TFA_NO_REQUEST_FOUND_IN_SESSION'	=> 'No se encontró ninguna solicitud en la sesión actual. ¿Lo envías a través de una página diferente?',
		'TFA_NOT_REGISTERED'				=> 'La clave de seguridad utilizada no se registró en tu cuenta',

		'FTA_NO_RESPONSE'                   => 'No se recibió respuesta',
		'TFA_SELECT_KEY'                    => 'Seleccionar tipo de clave',
		'TFA_NO_RESPONSE_RECEIVED'          => 'No recibimos una respuesta de tu clave de seguridad U2F. ¿Has pulsado el botón?',
		'TFA_NOT_SUPPORTED'                 => 'Navegador no compatible',
		'TFA_BROWSER_SEEMS_NOT_SUPPORTED'   => 'Lo sentimos, actualmente solo se admite Google Chrome.',
		'TFA_INSERT_KEY'                    => 'Introducir tu clave de seguridad',
		'TFA_INSERT_KEY_EXPLAIN'            => 'Introduce tu clave de seguridad en tu ordenador y haz clic en “Insertar clave”.',
		'TFA_START_AUTH'                    => 'Insertar clave',
		'TFA_NO_ACCESS'						=> '¿Parece que no tienes acceso a esta página?',
		'TFA_UNABLE_TO_UPDATE_SESSION'		=> 'No se pudo actualizar la sesión. Por favor, contacta con el Administrador del foro',
		'TFA_DISABLED'						=> 'La autenticación de dos factores ha sido deshabilitada',

		'TFA_OTP_KEY_LOG'					=> 'Clave OTP',
		'TFA_OTP_KEY_LOG_EXPLAIN'			=> 'Abre la aplicación de autenticación y toma la clave que se muestra actualmente en el campo de texto a continuación',
		'TFA_INCORRECT_KEY'					=> 'La clave proporcionada era incorrecta.',
		'TFA_NO_KEY_PROVIDED'				=> 'No se proporcionó clave',
		'TFA_KEY_REQUIRED'					=> 'Proporciona tu clave de seguridad',

		'TFA_BACKUP_KEY'			=> 'Claves de respaldo',
		'TFA_OTP'					=> 'OTP',
		'TFA_U2F'					=> 'U2F',

		'TFA_CP_TXT'				=> 'phpBB Two Factor Authentication',
		'TFA_CP_NAME'				=> 'paul999',

		'TFA_BACKUP_KEY_LOG'				=> 'Clave de respaldo',
		'TFA_BACKUP_KEY_LOG_EXPLAIN'		=> 'Proporciona una clave de respaldo, que no se haya utilizado antes.',

		'TFA_DOUBLE_PRIORITY'				=> 'La prioridad elegida (%d) para el módulo %s ya está en uso para el módulo %s',

		'TFA_SOMETHING_WENT_WRONG'			=> 'Algo salió mal durante la solicitud. Por favor, inténtalo de nuevo más tarde',

		// Module names
		'MODULE_U2F'        => 'U2F',

	)
);
