<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

require_once __DIR__ . '/Symfony/Component/ClassLoader/UniversalClassLoader.php';

if (!defined('KLOUT_FILE_PREFIX')) {
    define('KLOUT_FILE_PREFIX', __DIR__);
}

$classLoader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$classLoader->registerNamespaces(array(
    'Klout'    => KLOUT_FILE_PREFIX,
    'Guzzle'   => KLOUT_FILE_PREFIX,
    'Symfony'  => KLOUT_FILE_PREFIX,
    'Zend'     => KLOUT_FILE_PREFIX,
));

$classLoader->register();

return $classLoader;
