<?php

namespace Generator\Commands;

use Nette\Application\IResponse;
use Nette\Application\Responses\TextResponse;

final class InfoCommand extends AbstractCommand
{

    /**
     * @param array $params
     * @return IResponse
     */
    public function execute(array $params = [])
    {
        return new TextResponse("Generator is ready!");
    }
}
