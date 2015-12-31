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
     * Return if this module is enabled by the admin
     * (And all server requirements are met).
     *
     * Do not return false in case a specific user disabeld this module,
     * OR if the user is unable to use this specific module.
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
     * @return boolean
     */
    public function is_usable();

    /**
     * Check if the user can potentially use this.
     * This method is called at registration page.
     *
     * You can, for example, check if the current browser is suitable.
     *
     * @return boolean
     */
    public function is_potentially_usable();

    /**
     * Get the priority for this module.
     * A higher priority means more chance it gets selected.
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
     * @return void
     */
    public function login_start();

    /**
     * Actual login procedure
     * @return void
     */
    public function login();

    /**
     * Start of registration
     * @return void
     */
    public function register_start();

    /**
     * Actual registration
     * @return void
     */
    public function register();

    /**
     * This method is called for each row in the UCP.
     * Return a array with data, which is assigned to the template.
     *
     * @param $data
     * @return array
     */
    public function show_ucp_row($data);
}