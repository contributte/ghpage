<?php

namespace Generator;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Config\Adapters\NeonAdapter;
use Nette\DI\Helpers;
use Nette\InvalidStateException;
use Nette\Utils\Finder;
use Nette\Utils\Validators;

final class Extension extends CompilerExtension
{

    /** @var array */
    private $defaults = [
        'themes' => '%wwwDir%\themes',
        'output' => '%wwwDir\output',
        'theme' => NULL,
        'template' => NULL,
    ];

    /** @var array */
    private $themeDefaults = [
        'theme' => [
            'name' => NULL,
            'version' => NULL,
            'pages' => [],
            'copy' => [],
        ],
        'model' => NULL,
    ];

    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        // Add themes
        $params = [];
        $neon = new NeonAdapter();
        foreach (Finder::findFiles('theme.neon')->limitDepth(2)->from($config['themes']) as $file => $splfile) {
            // Parse config
            $expandConfig = ['themeDir' => dirname($splfile->getRealPath())];
            $themeConfig = Helpers::expand($neon->load($file), $expandConfig, TRUE);

            // Validate theme configs
            $this->validateConfig($this->themeDefaults, $themeConfig, 'themes');
            $this->validateConfig($this->themeDefaults['theme'], $themeConfig['theme'], 'theme');

            // Parse theme name
            $themeName = strtolower($themeConfig['theme']['name']);

            // Check duplicity
            if (array_key_exists($themeName, $params)) {
                throw new InvalidStateException('Theme "' . $themeName . '" is already defined.');
            }

            // Add to array
            $params[$themeName] = $themeConfig;
        }

        // Check if selected template is not null
        Validators::assertField($params, $config['theme'], NULL, 'template "%s"');

        $theme = $params[$config['theme']];

        // Add parameters to global parameters
        $builder->parameters['themes'] = [];
        $builder->parameters['themes']['theme'] = $theme['theme'];
        $builder->parameters['themes']['vars'] = $config['template'];
        $builder->parameters['themes']['output'] = $config['output'];

        // Add template model to container
        Compiler::parseServices($builder, ['services' => $theme['model']], $this->prefix('model'));

        // Add command manager (fake presenter)
        $builder->addDefinition($this->prefix('ui.manager'))
            ->setClass('Generator\UI\CommandManager');

        // Add template factory
        $builder->addDefinition($this->prefix('latte.templateFactory'))
            ->setClass('Generator\Latte\TemplateFactory')
            ->setAutowired();

        // Add commands
        $builder->addDefinition($this->prefix('commands.info'))
            ->setClass('Generator\Commands\InfoCommand')
            ->setInject();

        $builder->addDefinition($this->prefix('commands.generate'))
            ->setClass('Generator\Commands\GenerateCommand', [$builder->parameters['themes']])
            ->setInject();

        $builder->addDefinition($this->prefix('commands.deploy'))
            ->setClass('Generator\Commands\DeployCommand')
            ->setInject();

        $builder->addDefinition($this->prefix('commands.error'))
            ->setClass('Generator\Commands\ErrorCommand')
            ->setInject();
    }

    public function beforeCompile()
    {
        $builder = $this->getContainerBuilder();
        $builder->getDefinition('application.presenterFactory')
            ->addSetup('setMapping', [['*' => 'Generator\UI\*']]);

        if (($templateFactory = $builder->getDefinition('latte.templateFactory'))) {
            $templateFactory->setAutowired(FALSE);
        }
    }

}
