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

use paul999\u2f\Registration;
use paul999\u2f\RegistrationInterface;

class registration_helper extends Registration
{
	/** @var int  */
	public $id = 0;
}
