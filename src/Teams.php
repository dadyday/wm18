<?php
namespace My;

use Exception;

class Teams extends \ArrayObject {

    static function getByName($name): Team {
        return new Team($name);
    }
}

/**
 * @property-read string $html
 * @property-read string $flag
 */
class Team {
    use \Nette\SmartObject;

    public
        $name,
        $oCountry;

    function __construct($name) {
        $this->name = $name;
        $this->oCountry = Countries::getByName($name);
    }

    function getHtml() {
        return $this->oCountry->html;
    }
}
