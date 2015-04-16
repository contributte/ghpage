<?php

namespace Generator\Commands;

use Nette\Application\IResponse;
use Nette\Application\Responses\TextResponse;

final class DeployCommand extends AbstractCommand
{

    /**
     * @param array $params
     * @return IResponse
     */
    public function execute(array $params = [])
    {
        return new TextResponse("Deploy: not implemented");
    }
}
