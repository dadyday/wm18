<?php
require_once __DIR__.'/cfg.php';

use Tester\Assert as Is;

$oDi = require __DIR__.'/../app/bootstrap.php';

Is::type('\Nette\DI\Container', $oDi);

$oMatches = $oDi->getService('matches');
dump($oMatches);
Is::type(\My\Matches::class, $oMatches);

$oGroups = $oDi->getService('groups');
dump($oGroups);
Is::type(\My\Groups::class, $oGroups);

$oTeams = $oDi->getService('teams');
dump($oTeams);
Is::type(\My\Teams::class, $oTeams);

$oCountries = $oDi->getService('countries');
dump($oCountries);
Is::type(\My\Countries::class, $oCountries);

// items
$aCountry = $oCountries->getAll();
dump($aCountry);
Is::type('array', $aCountry);
Is::type(\My\Country::class, $aCountry['de']);

$aGroup = $oGroups->getAll();
dump($aGroup);
Is::type('array', $aGroup);
Is::type(\My\Group::class, $aGroup['E']);

$aTeam = $oTeams->getAll();
dump($aTeam);
Is::type('array', $aTeam);
Is::type(\My\Team::class, $aTeam['de']);
