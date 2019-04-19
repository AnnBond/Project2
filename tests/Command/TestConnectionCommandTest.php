<?php

namespace Tests\Command;

use Console\Command\TestConnectionCommand;
use Console\Exception\ConnectionException;
use Console\Service\Connection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

class TestConnectionCommandTest extends TestCase
{
    /**
     * @group slow
     */
    public function testSuccessfulRealExecute()
    {
        $connection = new Connection();
        $commandTester = new CommandTester(new TestConnectionCommand($connection));

        $db = 'test';

        $commandTester->execute(
            array(
                'host' => 'localhost',
                'port' => 3306,
                'db' => $db,
                'user' => 'root',
                'pass' => '',
            )
        );

        $output = $commandTester->getDisplay();
        $this->assertContains(sprintf("Connection test to database %s successful tested!", $db), $output);
    }

    /**
     * @group slow
     */
    public function testFailedRealExecute()
    {
        $connection = new Connection();
        $commandTester = new CommandTester(new TestConnectionCommand($connection));

        $db = 'test';

        $commandTester->execute(
            array(
                'host' => 'localhost',
                'port' => 3306,
                'db' => $db,
                'user' => 'root',
                'pass' => '',
            )
        );

        $output = $commandTester->getDisplay();
        $this->assertContains(sprintf("Connection test to database %s error", $db), $output);
    }

    public function testSuccessExecute()
    {
        $connection = $this->getConnectionMock();
        $connection
            ->expects(self::once())
            ->method('connect')
            ->willReturn(true);

        $commandTester = new CommandTester(new TestConnectionCommand($connection));

        $db = 'test';

        $commandTester->execute(
            array(
                'host' => 'localhost',
                'port' => 3306,
                'db' => $db,
                'user' => 'root',
                'pass' => '',
            )
        );

        $output = $commandTester->getDisplay();
        $this->assertContains(sprintf("Connection test to database %s successful tested!", $db), $output);
    }

    public function testFailedExecute()
    {
        $connection = $this->getConnectionMock();
        $connection
            ->expects(self::once())
            ->method('connect')
            ->willThrowException(new ConnectionException());

        $commandTester = new CommandTester(new TestConnectionCommand($connection));

        $db = 'test';

        $commandTester->execute(
            array(
                'host' => 'localhost',
                'port' => 3306,
                'db' => $db,
                'user' => 'root',
                'pass' => '',
            )
        );

        $output = $commandTester->getDisplay();
        $this->assertContains(sprintf("Connection test to database %s error", $db), $output);
    }

    public function testFailedVerboseExecute()
    {
        $connection = $this->getConnectionMock();
        $connection
            ->expects(self::once())
            ->method('connect')
            ->willThrowException(new ConnectionException());

        $commandTester = new CommandTester(new TestConnectionCommand($connection));

        $db = 'test';

        $commandTester->execute(
            array(
                'host' => 'localhost',
                'port' => 3306,
                'db' => $db,
                'user' => 'root',
                'pass' => '',
            ),
            array(
                'verbosity' => OutputInterface::VERBOSITY_VERBOSE
            )
        );

        $output = $commandTester->getDisplay();

        $this->assertContains(ConnectionException::class, $output);
        $this->assertContains(sprintf("Connection test to database %s error", $db), $output);
    }

    public function testGetInput()
    {
        $connection = $this->getConnectionMock();

        $commandTester = new CommandTester(new TestConnectionCommand($connection));

        $commandTester->execute(
            array(
                'host' => 'localhost',
                'port' => 3306,
                'db' => 'test',
                'user' => 'root',
                'pass' => 'pass',
            )
        );

        $this->assertEquals('3306', $commandTester->getInput()->getArgument('port'));
        $this->assertEquals('localhost', $commandTester->getInput()->getArgument('host'));
        $this->assertEquals('test', $commandTester->getInput()->getArgument('db'));
        $this->assertEquals('root', $commandTester->getInput()->getArgument('user'));
    }

    /**
     * @return Connection|\PHPUnit\Framework\MockObject\MockObject
     * @throws \ReflectionException
     */
    protected function getConnectionMock()
    {
        return $this
            ->getMockBuilder(Connection::class)
            ->setMethods(array('connect'))
            ->getMock();
    }
}
