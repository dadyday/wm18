<?php
namespace My;

use Nette;

trait FactoryTrait {
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
}
