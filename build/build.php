<?php 
// create with alias "project.phar"
$phar = new Phar('klout.phar', 0, 'klout.phar');
$phar->compressFiles(Phar::GZ);
$phar->startBuffering();
$phar->buildFromIterator(
    new RecursiveIteratorIterator(
     new RecursiveDirectoryIterator('/Users/mtougeron/src/klout-sdk-php/build/artifacts/staging')),
    '/Users/mtougeron/src/klout-sdk-php/build/artifacts/staging');
$phar->setStub($phar->createDefaultStub('/Users/mtougeron/src/klout-sdk-php/build/phar-stub.php'));

$phar->stopBuffering();

$phar = null;