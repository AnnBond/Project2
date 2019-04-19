<?php

namespace Console\Command;

use Console\Exception\ConnectionException;
use Console\Service\Connection;
use Console\Utils\ConnectionConfiguration;
use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class TableListCommand extends Command
{
    /**
     * @const string
     */
    public const COMMAND_NAME = 'tableList';

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * TestConnectionCommand constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
//        $this->connection = $this->getContainer()->get('app.connection');
        $this->connection = $connection;

        parent::__construct(static::COMMAND_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function configure()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription('All tables in database')
            ->setHelp('This command allows you to return all tables name')
            ->addArgument('host', InputArgument::REQUIRED, 'host')
            ->addArgument('port', InputArgument::REQUIRED, 'port')
            ->addArgument('db', InputArgument::REQUIRED, 'database name')
            ->addArgument('user', InputArgument::REQUIRED, 'user')
            ->addArgument('pass', InputArgument::OPTIONAL, 'pass');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $db = $input->getArgument('db');

        try {
            $database = $this->connection->connect(
                $this->getConfig($input),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            $tableList = $database->query('show tables')->fetchAll();

            $rows = [];
            foreach ($tableList as $tableDB) {
                $tableName = $tableDB["Tables_in_test"];
                $rowsCount = $database->query("SELECT COUNT(*) as count FROM $tableName")->fetch();

                $rows[] = [$tableName, $rowsCount['count']];

            }

            $table = new Table($output);
            $table
                ->setHeaders(['Tables'])
                ->setRows(
                    $rows
                );

            $table->render();

            $output->writeln(
                sprintf("<info>Result about all tables in database %s returned!</info>", $db)
            );
        } catch (ConnectionException $exception) {
            if ($output->isVerbose()) {
                $output->writeln((string)$exception);
            }

            $output->writeln(sprintf("<error>Result about all tables in database %s failed </error>", $db));

            return 1;
        }

        return 0;
    }

    /**
     * @param InputInterface $input
     * @return ConnectionConfiguration
     */
    protected function getConfig(InputInterface $input): ConnectionConfiguration
    {
        $host = $input->getArgument('host');
        $port = (int)$input->getArgument('port');
        $db = $input->getArgument('db');
        $user = $input->getArgument('user');
        $pass = $input->getArgument('pass');

        return new ConnectionConfiguration(
            $db,
            $user,
            $pass,
            $host,
            $port
        );
    }
}
