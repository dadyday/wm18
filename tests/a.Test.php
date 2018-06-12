<?php
require_once __DIR__.'/../vendor/autoload.php';

use Tester\Assert as Is;

Tester\Environment::setup();
//Tracy\Debugger::enable();

$oDi = require __DIR__.'/../app/bootstrap.php';

Is::type('\Nette\DI\Container', $oDi);

$o = $oDi->getService('class');
Is::type(\My\FatClass::class, $o);

$o = new \My\Own\AnotherClass();
Is::type(\My\Own\AnotherClass::class, $o);
