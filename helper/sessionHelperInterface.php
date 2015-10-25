<?php
/**
 *
 * 2FA extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 Paul Sohier
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\tfa\helper;


use phpbb\user;

interface sessionHelperInterface
{
    /**
     * Check if Two Factor authentication for this user is required
     *
     * @param int $user_id The user id for this user
     * @param bool $admin Is this user trying to login into the ACP?
     * @param array $userdata Optional user array, used to select permissions. If in need of permissions, and this paramter isn't provided,
     *              it will result in a extra query!
     * @param bool $try Try all options for the user. For example, while admin is false, this will still behave as like admin.
     * @return bool
     */
    public function isTfaRequired($user_id, $admin = false, $userdata = array(), $try = false);

    /**
     * Check if the user has two factor authentication added to his account.
     * 
     * @param array $user_id
     * @return bool
     */
    public function isTfaRegistered($user_id);
}