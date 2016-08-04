<?php
/**
 *
 * 2FA extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 Paul Sohier
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\tfa\modules;

interface module_interface
{
	/**
	 * Get a language key for this specific module.
	 * @return string
	 */
	public function get_translatable_name();

	/**
	 * Return the name of the current module
	 * This is for internal use only
	 * @return string
	 */
	public function get_name();

	/**
	 * Return if this module is enabled by the admin
	 * (And all server requirements are met).
	 *
	 * Do not return false in case a specific user disabled this module,
	 * OR if the user is unable to use this specific module,
	 * OR if a browser specific item is missing/incorrect.
	 * @return boolean
	 */
	public function is_enabled();

	/**
	 * Check if the current user is able to use this module.
	 *
	 * This means that the user enabled it in the UCP,
	 * And has it setup up correctly.
	 * This method will be called during login, not during registration/
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public function is_usable($user_id);

	/**
	 * Check if the user can potentially use this.
	 * This method is called at registration page.
	 *
	 * You can, for example, check if the current browser is suitable.
	 *
	 * @param int|boolean $user_id Use false to ignore user
	 * @return bool
	 */
	public function is_potentially_usable($user_id = false);

	/**
	 * Get the priority for this module.
	 * A lower priority means more chance it gets selected as default option
	 *
	 * There can be only one module with a specific priority!
	 * If there is already a module registered with this priority,
	 * a Exception might be thrown
	 *
	 * @return int
	 */
	public function get_priority();

	/**
	 * Start of the login procedure.
	 * @param int $user_id
	 * @return int
	 */
	public function login_start($user_id);

	/**
	 * Actual login procedure
	 * @param int $user_id
	 */
	public function login($user_id);

	/**
	 * If this module can add new keys (Or other things)
	 *
	 * @return boolean
	 */
	public function can_register();

	/**
	 * Start with the registration of a new security key.
	 * This page should return a name of a template, and
	 * it should assign the required variables for this template.
	 *
	 * @return string
	 */
	public function register_start();

	/**
	 * Do the actual registration of a new security key.
	 *
	 * @return boolean Result of the registration.
	 * @throws BadRequestHttpException
	 */
	public function register();

	/**
	 * This method is called to show the UCP page.
	 * You can assign template variables to the template, or do anything else here.
	 */
	public function show_ucp();

	/**
	 * Delete a specific row from the UCP.
	 * The data is based on the data provided in show_ucp.
	 * @param int $key
	 * @return void
	 */
	public function delete($key);
}
