<?php
require_once __DIR__.'/cfg.php';

$oDi = require __DIR__.'/../app/bootstrap.php';
$oApp = $oDi->getByType(Nette\Application\Application::class);
$oApp->run();
