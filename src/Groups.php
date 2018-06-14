<?php
namespace My;

use Exception;

class Groups extends \ArrayObject {

    static
        $configFile = null;

    static function factory($configFile): Groups {
        static::$configFile = $configFile;
        return new static();
    }

    function __construct() {
        if (!static::$configFile) throw new Exception('Groups::$configFile is not set');
        if (!file_exists(static::$configFile)) throw new Exception(static::$configFile." not found");
        $aData = include(static::$configFile);
        $aList = [];
        foreach ($aData as $code => $name) {
            $aList[$code]= new Group($code, $name);
        }
        parent::__construct($aList);
    }
}

/**
 * @property-read string $html
 * @property-read \ArrayObject $matches
 */
class Group {
    use \Nette\SmartObject;

    public
        $id,
        $name,
        $oMatches;

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
        $this->oMatches = Matches::create($this, $data);
    }

    function getMatches() {
        return $this->oMatches;
    }

    function getHtml() {
        $matches = '';
        foreach ($this->oMatches as $oMatch) {
            $matches .= $oMatch->rowHtml;
        }
        return "<div>
            $this->name
            <table>

                $matches
            </table>
            </div>";
    }
}
