<?php
namespace My;

use Exception;

class Countries extends \ArrayObject {

    static $configFile;

	/** @return \My\Countries */
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
        if (!static::$configFile) throw new Exception('Countries::$configFile is not set');
        if (!file_exists(static::$configFile)) throw new Exception(static::$configFile." not found");

        $oData = new Data();
        $aList = [];

        foreach ($oData->load(static::$configFile) as $aData) {
            $code = strtolower($aData[0]);
            $aList[$code]= new Country($code, $aData[2]);
        }
        parent::__construct($aList);
    }

    protected function _getByName($name) {
        foreach ($this as $code => $oCountry) {
            if ($oCountry->name == $name) return $oCountry;
        }
        return null;
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
