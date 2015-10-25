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

/**
 * helper method which is used to detect if a user needs to use 2FA
 */
class sessionHelper implements sessionHelperInterface
{


	/**
	 * Constructor
	 *
	 * @access public
	 */
	public function __construct()
	{
	}

	/**
	 * @param array $user_row
	 * @return bool
	 */
	public function isTfaRequired($user_row)
	{
		// TODO: Implement isTfaRequired() method.
		return true;
	}

	/**
	 * Check if the user has two factor authentication added to his account.
	 *
	 * @param array $user_row
	 * @return bool
	 */
	public function isTfaRegistered($user_row)
	{
		// TODO: Implement isTfaRegistered() method.
		return true;
	}
}
