<?php
require_once __DIR__.'/../vendor/autoload.php';

use Nette\Configurator;

$oConfig = new Configurator();
$oConfig->setTempDirectory(__DIR__.'/../temp');

$oConfig->addConfig(__DIR__.'/config.neon');

$oDi = $oConfig->createContainer();
return $oDi;
