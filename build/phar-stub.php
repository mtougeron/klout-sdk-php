<?php

Phar::mapPhar('klout.phar');

define('KLOUT_FILE_PREFIX', 'phar://klout.phar');

return (require 'phar://klout.phar/klout-autoloader.php');

__HALT_COMPILER();