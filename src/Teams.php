<?php
namespace My;

use Exception;

class Teams extends Repo {
    static $oInst;

    /** @return \My\Teams */
    static function factory($configFile) {
        return parent::factory($configFile);
    }

    /** @var \My\Countries @inject */
    public $oCountries;

    function loadData(&$aList, $i, $aScore) {
        $oTeam = new Team($aScore[2], $aScore[3]);
        $oTeam->rank = $i+1;
        $oTeam->setScore($aScore);
        $aList[$oTeam->short] = $oTeam;
    }

    protected function _getByName($name) {
        $oItem = $this->getByProperty('name', $name);
        if (is_null($oItem)) throw new \UnexpectedValueException("team $name not found");
        return $oItem;
    }
}

/**
 * @property-read \My\Country $country
 * @property-read string $flag
 */
class Team {
    use \Nette\SmartObject;

    public
        $short,
        $name,
        $country,

        $rank,

        $matches,
        $wins,
        $ties,
        $lost,
        $score,

        $goal,
        $anti,
        $diff;

    function __construct($short, $name) {
        $this->short = strtolower($short);
        $this->name = $name;
        $this->country = Countries::getByName($name);
    }

    function setScore($aScore) {
        list(
            $continent, $quali,
            $this->short, $this->name,
            $this->matches, $this->wins, $this->ties, $this->lost,
            $this->goal, $this->anti
        ) = $aScore;

        $this->score = $this->wins*3 + $this->ties;
        $this->diff = $this->goal-$this->anti;

        $this->wins /= $this->matches;
        $this->ties /= $this->matches;
        $this->lost /= $this->matches;
        $this->score /= $this->matches;

        $this->goal /= $this->matches;
        $this->anti /= $this->matches;
        $this->diff /= $this->matches;
    }
}
