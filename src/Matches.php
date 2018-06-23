<?php
namespace My;

use Exception;
use DateTime;

class Matches extends Repo {
    static $oInst;

    /** @return \My\Matches */
    static function factory($configFile) {
        return parent::factory($configFile);
    }

    /** @var \My\Teams @inject */
    public $oTeams;

    function loadData(&$aList, $key, $aData) {
        #bdump($aData, 'matches'.$key);
        foreach ($aData as $i => $aMatch) {
            $id = $key.($i+1);
            $aList[$id] = new Match($id, $key, $aMatch);
        }
    }

    function getForDay($date) {
        $date = $date->format('Ymd');
        $aRet = [];
        foreach ($this->aList as $oItem) {
            if ($oItem->time->format('Ymd') == $date) $aRet[] = $oItem;
        }
        #bdump($aRet, $date);
        return $aRet;
    }
}

/**
 * @property-read My\Team $team1
 * @property-read My\Team $team2
 */
class Match {
    use \Nette\SmartObject;

    public
        $id,
        $group,
        $oTeam1,
        $oTeam2,
        $time;

    function __construct($id, $group, $aData) {
        $this->id = $id;
        $this->group = $group;
        $this->oTeam1 = Teams::getByName($aData[0]);
        $this->oTeam2 = Teams::getByName($aData[1]);
        $this->time = new DateTime($aData[2]);
    }

    function getTeam1() {
        return $this->oTeam1;
    }

    function getTeam2() {
        return $this->oTeam2;
    }
}
