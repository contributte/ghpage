<?php

namespace Generator;

use Nette;
use Nette\Application\Routers\CliRouter;
use Nette\Application\Routers\RouteList;
use Nette\Http\IRequest;
use Nette\Application\Request;

final class GeneratorRoute extends CliRouter
{

    public function __construct()
    {
        parent::__construct(['action' => 'CommandManager']);
    }

    /**
     * @param IRequest $httpRequest
     * @return Request|NULL
     */
    public function match(IRequest $httpRequest)
    {
        $request = parent::match($httpRequest);
        if ($request != NULL){
            // Set resolver
            $request->setPresenterName('CommandManager');
        }

        return $request;
    }

}
