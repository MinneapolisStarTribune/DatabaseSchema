<?php
$autoload = realpath(__DIR__.'/../vendor/autoload.php');
if(! file_exists($autoload)) {
    $autoload = realpath(__DIR__.'/../../vendor/autoload.php');
}
if(! file_exists($autoload)) {
    $autoload = realpath(__DIR__.'/../../../vendor/autoload.php');
}
if(! file_exists($autoload)) {
    $autoload = realpath(__DIR__.'/../../../../vendor/autoload.php');
}
if ( ! @include $autoload )
{
    die(<<<'EOT'
You must set up the project dependencies, run the following commands:
wget http://getcomposer.org/composer.phar
php composer.phar install --dev

You can then run tests by calling:

phpunit
EOT
       );
}
