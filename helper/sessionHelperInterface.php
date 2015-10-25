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
     * @param array $user_row
     * @return bool
     */
    public function isTfaRequired($user_row);

    /**
     * Check if the user has two factor authentication added to his account.
     * 
     * @param array $user_row
     * @return bool
     */
    public function isTfaRegistered($user_row);
}