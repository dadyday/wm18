<?php
require_once __DIR__.'/../vendor/autoload.php';

use Nette\Configurator;

$oConfig = new Configurator();
$oConfig->setTempDirectory(__DIR__.'/../temp');

$oConfig->addConfig(__DIR__.'/config.neon');

My\Countries::$configFile = __DIR__.'/data/countries.csv';
My\Groups::$configFile = __DIR__.'/data/groups.php';
My\Teams::$configFile = __DIR__.'/data/scores.php';

$oDi = $oConfig->createContainer();
return $oDi;
