<?php
namespace My;

use Exception;


class Countries extends Repo {
    static $oInst;

    /** @return \My\Countries */
    static function factory($configFile) {
        return parent::factory($configFile);
    }

    function loadData(&$aList, $key, $aData) {
        $code = strtolower($aData['code']);
        $name = $aData['de'];
        $aList[$code]= new Country($code, $name);
    }

    protected function _getByName($name) {
        $oItem = $this->getByProperty('name', $name);
        if (is_null($oItem)) throw new \UnexpectedValueException("country $name not found");
        return $oItem;
    }
}

/**
 * @property-read string $html
 * @property-read string $flag
 */
class Country {
    use \Nette\SmartObject;

    public
        $code,
        $name;

    function __construct($code, $name) {
        $this->code = $code;
        $this->name = $name;
    }

    function getFlag() {
        return "<span class=\"flag-icon flag-icon-$this->code\"></span>";
    }

    function getHtml($align = 'left') {
        $flag = $this->getFlag();
        switch ($align) {
            case 'right':
                return "<div>$this->name$flag</div>";
            default:
                return "<div>$flag$this->name</div>";
        }
    }
}
