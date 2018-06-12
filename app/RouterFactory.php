<?php
namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class RouterFactory {
    use Nette\StaticClass;

    /**
     * @return Nette\Application\Routers\RouteList;
     */
    public static function createRouter(): RouteList {
        $router = new RouteList;
        $router[] = new Route('<presenter>/<action>', 'Home:default');
        return $router;
    }
}
