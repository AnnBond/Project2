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
        $tableList->addTag('console.command');

        $testConnection = new Definition(TestConnectionCommand::class);
        $testConnection->addTag('console.command');

        $container->addDefinitions([
            TestConnectionCommand::class => $tableList,
            TableListCommand::class => $tableList,
            Application::class => $application,
        ]);

        $commandPass = new CommandPass();
        $commandPass->process($container);

        $methodCalls = $application->getMethodCalls();

        list($method, $calls) = $methodCalls[0];
        self::assertEquals('add', $method);
        self::assertTrue(in_array((string)$calls[0], array(TableListCommand::class, TestConnectionCommand::class)));

        list($method, $calls) = $methodCalls[1];
        self::assertEquals('add', $method);
        self::assertTrue(in_array((string)$calls[0], array(TableListCommand::class, TestConnectionCommand::class)));

        $this->assertTrue($application->hasMethodCall('add'));
        $this->assertCount(2, $application->getMethodCalls());

    }
}
