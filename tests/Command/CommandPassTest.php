<?php

namespace Tests\Command;

use Console\Command\TableListCommand;
use Console\Command\TestConnectionCommand;
use Console\DependencyInjection\Compiler\CommandPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class CommandPassTest extends TestCase
{

    public function testProcess()
    {
        $container = new ContainerBuilder();

        $application = new Definition(Application::class);
        $tableList = new Definition(TableListCommand::class);
        $testConnection = new Definition(TestConnectionCommand::class);
        $tableList->addTag('console.command');
        $testConnection->addTag('console.command');

        $container->addDefinitions([
            TestConnectionCommand::class => $tableList,
            TableListCommand::class => $tableList,
            Application::class => $application,
        ]);

        $commandPass = new CommandPass();
        $commandPass->process($container);

        $this->assertTrue($application->hasMethodCall('add'));
        $this->assertCount(2, $application->getMethodCalls());

    }
}
