#!/usr/bin/env php
<?php

use Console\DependencyInjection\Compiler\CommandPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

set_time_limit(0);

require dirname(__DIR__).'/vendor/autoload.php';


$containerBuilder = new ContainerBuilder();
$containerBuilder->addCompilerPass(new CommandPass());
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('services.yaml');
$containerBuilder->compile();


$application = $containerBuilder->get(Application::class);
$application->run();
