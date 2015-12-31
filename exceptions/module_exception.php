<?php
/**
 *
 * 2FA extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 Paul Sohier
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\tfa\exceptions;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class module_exception extends BadRequestHttpException
{

}