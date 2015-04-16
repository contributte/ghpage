<?php

namespace Generator\Commands;

use Nette\Application\IResponse;
use Nette\Application\Responses\TextResponse;

final class ErrorCommand extends AbstractCommand
{

    /**
     * @param array $params
     * @return IResponse
     */
    public function execute(array $params = [])
    {
        return new TextResponse("Error: " . $params['message']);
    }
}
