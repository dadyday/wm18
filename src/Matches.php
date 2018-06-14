<?php
namespace My;

use Exception;
use DateTime;

class Matches extends \ArrayObject {

    static function create($oGroup, $aData) {
        return new static($oGroup, $aData);
    }

    function __construct($oGroup, $aData) {
        $aList = [];
        foreach ($aData as $i => $aData) {
            $id = $oGroup->id.($i+1);
            $aList[$id] = new Match($id, $aData);
        }
        parent::__construct($aList);
    }
}

/**
 * @property-read string $rowHtml
 */
class Match {
    use \Nette\SmartObject;

    public
        $id,
        $oTeam1,
        $oTeam2,
        $time;

    function __construct($id, $aData) {
        $this->id = $id;
        $this->oTeam1 = Teams::getByName($aData[0]);
        $this->oTeam2 = Teams::getByName($aData[1]);
        $this->time = new DateTime($aData[2]);
    }
}
