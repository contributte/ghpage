<?php

namespace Generator\Commands;

use Generator\Latte\TemplateFactory;
use Generator\Utils\Helpers;
use Latte\Engine;
use Nette\Application\IResponse;
use Nette\Application\Responses\TextResponse;
use Nette\DI\Container;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;

final class GenerateCommand extends AbstractCommand
{

    /** @var TemplateFactory @inject */
    public $templateFactory;

    /** @var array */
    private $parameters;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param array $params
     * @return IResponse
     */
    public function execute(array $params = [])
    {
        $vars = $this->parameters['vars'];
        $theme = $this->parameters['theme'];
        $output = $this->parameters['output'];

        // Force create folder
        Helpers::purge($output);

        // Create Latte
        $template = $this->templateFactory->createTemplate();

        // Convert all latte files to static html files
        foreach (Finder::find('*.latte')->in(implode(', ', (array) $theme['pages'])) as $file => $splfile) {
            /** @var \SplFileInfo $splfile */
            $template->setFile($splfile->getRealPath());
            $template->setParameters($params);
            $template->setParameters($vars);
            file_put_contents($output . DS . $splfile->getBasename('.latte') . '.html', $template->__toString());
        }

        // Move all files/folders to output
        foreach ($theme['copy'] as $copy) {
            FileSystem::copy($copy, $output . DS . str_replace($theme['pages'], NULL, $copy), TRUE);
        }

        return new TextResponse("Generate: done");
    }
}
