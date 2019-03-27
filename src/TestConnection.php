<?php namespace Console;

use PDO;
use PDOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class TestConnection extends Command
{
    public function configure()
    {
        $this->setName('Connection')
            ->setDescription('Test if connection are working fine')
            ->setHelp('This command allows you to check if connection success')
            ->addArgument('host', InputArgument::REQUIRED, 'host')
            ->addArgument('port', InputArgument::REQUIRED, 'port')
            ->addArgument('db', InputArgument::REQUIRED, 'database name')
            ->addArgument('user', InputArgument::REQUIRED, 'user')
            ->addArgument('pass', InputArgument::REQUIRED, 'pass');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $host = $input->getArgument('host');
        $port = $input->getArgument('port');
        $db = $input->getArgument('db');
        $user = $input->getArgument('user');
        $pass = $input->getArgument('pass');

        try {
            $dsn = "mysql:host=$host:$port;dbname=$db;charset=utf8";
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $connection = new PDO($dsn, $user, $pass, $opt);
            $output->writeln('<info>Connection test to database ' . $input->getArgument('db') . ' successful tested!</info>');

            return true;

        } catch (PDOException $e) {

            if ($output->isVerbose()) {
                $output->writeln($e->getMessage());
            }

            $output->writeln('<error>Connection test to database ' . $input->getArgument('db') . '  error </error>');
        }
    }
}