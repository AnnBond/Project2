#!/usr/bin/env php
<?php

use Console\Command\TableListCommand;
use Console\Command\TestConnectionCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

set_time_limit(0);

require dirname(__DIR__).'/vendor/autoload.php';


$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('services.yaml');

$connection = $containerBuilder->get("app.connection");
$application = new Application();

//$application->setCommandLoader(new class implements \Symfony\Component\Console\CommandLoader\CommandLoaderInterface {
//
//
//    /**
//     * Loads a command.
//     *
//     * @param string $name
//     *
//     * @return \Symfony\Component\Console\Command\Command
//     *
//     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
//     */
//    public function get($name)
//    {
//    }
//
//    /**
//     * Checks if a command exists.
//     *
//     * @param string $name
//     *
//     * @return bool
//     */
//    public function has($name)
//    {
//    }
//
//    /**
//     * @return string[] All registered command names
//     */
//    public function getNames()
//    {
//    }
//});


$command = new TestConnectionCommand($connection);
$tableList = new TableListCommand($connection);

$application->add($command);
$application->add($tableList);
$application->setDefaultCommand($tableList->getName(), true);
$application->run();
