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
		'ACP_TFA_SETTINGS'			=> 'Tweefactorauthenticatie instellingen',

		// As we are re-using the acp_board template, we can't add custom stuff to that page.
		// As such, we do need to have some HTML here :(.
		'ACP_TFA_SETTINGS_EXPLAIN'	=> 'Hier kan je de instellingen voor twee factor authenticatie wijzigen.
										De voorgestelde configuratieoptie is om in het algemeen geen tweefactorauthenticatie te vereisen en alleen voor het berheerderspaneel.<br /><br />
										De volgende browser(s) worden ondersteund door U2F:
										<ul>
											<li>Google Chrome (Vanaf versie 41)</li>
										</ul>
										Niet onderseunt:
										<ul>
											<li>Internet Explorer</li>
											<li>Edge</li>
											<li>Firefox</li>
											<li>Safari</li>
										</ul>
										<p>Verschillende browserleveranciers hebben echter beloofd dat het mogelijk wordt ondersteund in de toekomst.
										Wanneer een browser deze funtie niet ondersteunt is niet mogelijk om de optie U2F te selecteren.</p>
										
										<h2>Ondersteuning ontvangen</h2>
										<p>Ondersteuning wordt alleen gegeven op www.phpbb.com, in de extension <a href="https://www.phpbb.com/customise/db/extension/phpbb_two_factor_authentication/" target="_blank">customisations database</a>. Lees eerst de FAQ voordat je een vraag stelt.</p>
										
										<h2>Wil je de ontwikkeling van deze extensie ondersteunen?</h2>
										<p>Deze extensie is volledig in mijn vrije tijd ontwikkeld. Je kunt me helpen door een kleine donatie te doen om deze extensie verder te laten ontwikkelen.</p>
										<ul>
											<li>Word een sponser op github: <a href="https://github.com/sponsors/paul999" target="_blank">https://github.com/sponsors/paul999</a></li>
											<li>Maak een paypal donatie: <a href="https://paypal.me/sohier" target="_blank">https://paypal.me/sohier</a></li>
											<li>Maak een donatie via bunq: <a href="https://bunq.me/Paul999" target="_blank">https://bunq.me/Paul999</a></li>
										</ul>
										',
		'TFA_REQUIRES_SSL'			=> 'Het lijkt er op dat dat er geen gebruikt wordt gemaakt van een beveiligde verbinding. Deze extensie vereist een beveiligde SSL-verbinding om de beveiligingssleutels te laten werken. Gebruikers kunnen deze optie niet kiezen, tenzij je een beveiligde verbinding met forum inschakelt.',

		'TFA_MODE'						=> 'Tweefactorauthenticatie modes',
		'TFA_MODE_EXPLAIN'				=> 'Hier kan je selecteren voor welke gebruikers (indien aanwezig) het vereist is om de tweefactorauthenticatiemodus te gebruiken. Als je "Tweefactorauthenticatie uitgeschakeld" selecteert, wordt de functionaliteit volledig uitgeschakeld.',
		'TFA_DISABLED'					=> 'Tweefactorauthenticatie uitgeschakeld',
		'TFA_NOT_REQUIRED'				=> 'Tweefactorauthenticatie niet vereist',
		'TFA_REQUIRED_FOR_ACP_LOGIN'	=> 'Tweefactorauthenticatie vereist voor alleen ACP login',
		'TFA_REQUIRED_FOR_ADMIN'		=> 'Tweefactorauthenticatie vereist voor alle beheerders',
		'TFA_REQUIRED_FOR_MODERATOR'	=> 'Tweefactorauthenticatie vereist voor alle moderators en beheerders',
		'TFA_REQUIRED'					=> 'Tweefactorauthenticatie vereist voor alle gebruikers',

		'TFA_ACP'           => 'Tweefactorauthenticatie vereist voor het beheerderspaneel',
		'TFA_ACP_EXPLAIN'   => 'Indien ingesteld op nee, hoeven beheerders geen tweefactorauthenticatiesleutel te gebruiken bij het inloggen op het ACP. Het uitschakelen hiervan wordt niet aanbevolen.'
	)
);
