<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

Phar::mapPhar('klout.phar');

define('KLOUT_FILE_PREFIX', 'phar://klout.phar');

return (require 'phar://klout.phar/klout-autoloader.php');

__HALT_COMPILER();
