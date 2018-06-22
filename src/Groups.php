<?php
namespace My;

use Exception;
use Nette;

class Groups extends Repo {

    static $oInst;

    /** @return \My\Groups */
    static function factory($configFile) {
        return parent::factory($configFile);
    }

    /** @var \My\Matches $oMatches @inject */
    public $oMatches;

    function loadData(&$aList, $key, $data) {
        $aList[$key]= new Group($key, $data);
    }
}
/**
 * @property-read array $matches
 */
class Group {
    use \Nette\SmartObject;

    public
        $id,
        $name;

    function __construct($id, $data) {
        $this->id = $id;
        if (preg_match('~^[A-Z]$~', $id, $aMatch)) {
            $this->name = 'Gruppe '.$id;
        }
        else if (preg_match('~^([AVH])F$~', $id, $aMatch)) {
            switch ($aMatch[1]) {
                case 'A': $this->name = 'Achtelfinale'; break;
                case 'V': $this->name = 'Viertelfinale'; break;
                case 'H': $this->name = 'Halbfinale'; break;
            }
        }
        else {
            $this->name = $id;
        }
    }

    function getMatches() {
        $oMatches = Matches::findByProperty('group', $this->id);
        return $oMatches;
    }
}
