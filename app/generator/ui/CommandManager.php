<?php

namespace Generator\UI;

use Nette\Application\IResponse;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;

final class CommandManager extends Presenter
{

    /**
     * @param Request $request
     */
    public function run(Request $request)
    {
        $params = $request->getParameters();
        $command = array_shift($params);

        return $this->resolve(strtolower($command), $params);
    }

    /**
     * @param string $command
     * @param array $params
     * @return IResponse
     */
    private function resolve($command, $params)
    {
        switch ($command) {
            case 'generate':
                return $this->context->createService('generator.commands.generate')->execute($params);
            case 'deploy':
                return $this->context->createService('generator.commands.deploy')->execute($params);
            case 'info':
                return $this->context->createService('generator.commands.info')->execute($params);
            default:
                $params['message'] = "Uknown command '$command'";
                return $this->context->createService('generator.commands.error')->execute($params);
        }
    }
}
