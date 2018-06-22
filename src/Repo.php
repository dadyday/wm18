<?php
namespace My;

use Nette;

abstract class Repo {

    static $oInst;

    static function factory($configFile) {
        return static::$oInst = new static($configFile);
    }

    static function instance() {
        return static::$oInst;
    }

    static function __callStatic($name, $args) {
        $oInst = static::instance();
        return call_user_func_array([$oInst, $name], $args);
    }

    protected
        $configFile,
        $aList = [];

    function __construct($configFile) {
        if (!$configFile) throw new Exception(static::class.'::$configFile is not set');
        if (!file_exists($configFile)) throw new Exception($configFile." not found");
        $this->configFile = $configFile;
    }

    function _load() {
        if ($this->aList) return;
        $this->aList = [];

        foreach (Data::load($this->configFile) as $key => $aData) {
            $this->loadData($this->aList, $key, $aData);
        }
    }

    function __call($name, $args) {
        if (method_exists($this, '_'.$name)) {
            $this->_load();
            return call_user_func_array([$this, '_'.$name], $args);
        };
        throw new \BadMethodCallException(static::class."::$name not defined");
        #return $this->smartCall($name, $args);
    }

    protected function _getAll() {
        return $this->aList;
    }

    protected function _getByKey($key) {
        return isset($this->aList[$key]) ? $this->aList[$key] : null;
    }

    protected function _getByProperty($name, $value) {
        foreach ($this->aList as $key => $oObj) {
            if (!isset($oObj->$name)) throw new \UnexpectedValueException('object of class '.get_class($oObj)." has no property $name");
            if ($oObj->$name == $value) return $oObj;
        }
        return null;
    }

    protected function _findByProperty($name, $value) {
        $aRet = [];
        foreach ($this->aList as $key => $oObj) {
            if (!isset($oObj->$name)) throw new \UnexpectedValueException('object of class '.get_class($oObj)." has no property $name");
            if ($oObj->$name == $value) {
                $aRet[$key] = $oObj;
            };
        }
        return $aRet;
    }

    abstract function loadData(&$aList, $key, $oData);
}
