<?php

namespace Tests\Command;

use Console\Command\TableListCommand;
use Console\Service\Connection;
use Console\Utils\ConnectionConfiguration;
use PDO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

class TableListCommandTest extends TestCase
{

    /**
     * @group slow
     */
    public function testSuccessfulRealExecute()
    {
        $connection = new Connection();
        $commandTester = new CommandTester(new TableListCommand($connection));

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
        $this->assertStringContainsString(sprintf("Result about all tables in database %s returned!", $db), $output);
    }

    public function testSuccessExecute()
    {
        $connection = $this->createMock(Connection::class);

        $connection
            ->expects(self::once())
            ->method('connect')
            ->willReturn($this->getPDO());

        $commandTester = new CommandTester(new TableListCommand($connection));

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

        $this->assertStringContainsString(sprintf("Result about all tables in database %s returned!", $db), $output);
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

    private function getConfig() {
        return new ConnectionConfiguration(
            'test',
            'root',
            '',
            'localhost',
            3306);
    }

    private function getOptions() {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true
        ];
    }

    private function getPDO() {
        return new PDO($this->getConfig()->getDSN(),
            $this->getConfig()->getUsername(),
            $this->getConfig()->getPassword(),
            $this->getOptions()
        );
    }
}
