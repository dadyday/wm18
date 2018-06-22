<?php
require_once __DIR__.'/cfg.php';

use Tester\Assert as Is;

$oDi = require __DIR__.'/../app/bootstrap.php';

Is::type('\Nette\DI\Container', $oDi);

#$oMatches = $oDi->getByType(\My\Matches::class);
#Is::type(\My\Matches::class, $oMatches);
#dump($oMatches);

$oGroups = $oDi->getService('groups');
Is::type(\My\Groups::class, $oGroups);
dump($oGroups);

$oGroup = $oGroups->getByKey('A');
Is::type(\My\Group::class, $oGroup);
dump($oGroup);

$aMatch = $oGroup->matches;
dump($aMatch);

Is::type('array', $aMatch);
Is::same(6, count($aMatch));
Is::type(\My\Match::class, current($aMatch));
//*/
