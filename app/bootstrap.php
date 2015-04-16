<?php

// Require via composer
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/shortcuts.php';

// Create configurator
$configurator = new Nette\Configurator;

// Enable debugger
//$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP
$configurator->setDebugMode(TRUE);
$configurator->enableDebugger(__DIR__ . '/log');
$configurator->setTempDirectory(__DIR__ . '/temp');

// Create robot loader
$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

// Add config
$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/generator.neon');

// Create container
$container = $configurator->createContainer();

return $container;
