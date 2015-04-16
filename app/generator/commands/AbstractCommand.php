<?php

namespace Generator\Commands;

use Nette\Application\AbortException;
use Nette\Application\IResponse;
use Nette\Application\UI\Presenter;
use Nette\Object;

abstract class AbstractCommand extends Object
{

    /**
     * @param array $params
     * @return IResponse
     */
    abstract function execute(array $params = []);
}
