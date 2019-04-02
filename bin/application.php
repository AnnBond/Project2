#!/usr/bin/env php
<?php

use Console\Command\TestConnectionCommand;
use Console\Service\Connection;
use Symfony\Component\Console\Application;

set_time_limit(0);

require dirname(__DIR__).'/vendor/autoload.php';

$application = new Application();
$connection = new Connection();
$command = new TestConnectionCommand($connection);

$application->add($command);
$application->setDefaultCommand($command->getName(), true);
$application->run();
