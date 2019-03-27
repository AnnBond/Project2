<?php
namespace Tests\Command;

use Console\TestConnection;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Tests\Tester\CommandTesterTest;

class ConnectionCommandTest extends CommandTesterTest
{

    public function testExecute()
    {
        $application = new Application('Console App', 'v1.0.0');
        $application->add(new TestConnection());

        $this->command = $application->find('Connection');

        $this->tester = new CommandTester($this->command);

        $this->tester->execute(array(
            'command' => $this->command->getName(),
            'host' => 'localhostee',
            'port' => '3306',
            'db' => 'test',
            'user' => 'root',
            'pass' => '',
        ));

        $output = $this->tester->getDisplay();
        $this->assertContains('Connection test to database test successful tested!', $output);
    }


    public function testGetInput()
    {

        $application = new Application('Console App', 'v1.0.0');
        $application->add(new TestConnection());

        $this->command = $application->find('Connection');

        $this->tester = new CommandTester($this->command);

        $this->tester->execute(array(
            'command' => $this->command->getName(),
            'host' => 'localhost',
            'port' => '3306',
            'db' => 'test',
            'user' => 'root',
            'pass' => '',
        ));

        $this->assertEquals('3306', $this->tester->getInput()->getArgument('port'));
        $this->assertEquals('localhost', $this->tester->getInput()->getArgument('host'));
        $this->assertEquals('test', $this->tester->getInput()->getArgument('db'));
        $this->assertEquals('root', $this->tester->getInput()->getArgument('user'));
    }

}