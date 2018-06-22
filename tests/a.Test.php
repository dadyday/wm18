<?php
require_once __DIR__.'/cfg.php';

use Tester\Assert as Is;

Tester\Environment::setup();
//Tracy\Debugger::enable();

$oDi = require __DIR__.'/../app/bootstrap.php';

Is::type('\Nette\DI\Container', $oDi);

$o = $oDi->getService('groups');
Is::type(\My\Groups::class, $o);

$m = $oDi->getService('matches');
Is::type(\My\Matches::class, $m);

$o = $o->getByKey('A');
Is::type(\My\Group::class, $o);
$m = $o->matches;
dump($m);
Is::type('array', $m);
Is::type(\My\Match::class, current($m));
