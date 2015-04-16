<?php

namespace Generator;

use Nette;
use Nette\Application\Routers\CliRouter;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter()
    {
        return new GeneratorRoute();
    }

}
