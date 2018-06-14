<?php
namespace My;

use Exception;

class Teams extends \ArrayObject {

    static $configFile;

	/** @return \My\Teams */
    static function factory($configFile) {
        static::$configFile = $configFile;
        return static::instance();
    }

    static function instance() {
        static $oInst = null;
        if (is_null($oInst)) $oInst = new static();
        return $oInst;
    }

    static function __callStatic($name, $args) {
        $oInst = static::instance();
        return call_user_func_array([$oInst, '_'.$name], $args);
    }

    protected
        $aList = [];

    function __construct() {
        if (!static::$configFile) throw new Exception('Teams::$configFile is not set');
        if (!file_exists(static::$configFile)) throw new Exception(static::$configFile." not found");

        $oData = new Data();
        $aList = [];

        $rank = 1;
        foreach ($oData->load(static::$configFile) as $aData) {
            $oTeam = new Team($aData[2], $aData[3]);
            $oTeam->rank = $rank++;
            $oTeam->setScore($aData);
            $aList[$oTeam->short] = $oTeam;
        }

        parent::__construct($aList);
    }

    protected function _getByName($name) {
        foreach ($this as $oTeam) {
            if ($oTeam->name == $name) return $oTeam;
        }
        return null;
    }
}

/**
 * @property-read string $html
 * @property-read string $flag
 */
class Team {
    use \Nette\SmartObject;

    public
        $short,
        $name,
        $oCountry,

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
        $this->oCountry = Countries::getByName($name);
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
