<?php
$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->notName('autoload_classmap.php')
    ->notName('autoload_function.php')
    ->notName('LICENSE')
    ->notName('README.md')
    ->notName('.php_cs')
    ->notName('composer.*')
    ->notName('*.js')
    ->notName('*.css')
    ->notName('*.scss')
    ->notName('*.ico')
    ->notName('*.phar')
    ->notName('*.png')
    ->notName('*.jpg')
    ->notName('*.gif')
    ->notName('*.xml')
    ->notName('*.txt')
    ->notName('*.swf')
;

return Symfony\CS\Config\Config::create()
    ->finder($finder)
;
