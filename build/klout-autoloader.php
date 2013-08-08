<?php

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
