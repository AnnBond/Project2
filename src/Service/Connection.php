<?php

namespace Console\Service;

use Console\Exception\ConnectionException;
use Console\Utils\ConnectionConfiguration;
use PDO;
use PDOException;

class Connection
{

    /**
     * @param ConnectionConfiguration $configuration
     * @param array $options
     * @return PDO
     * @throws ConnectionException
     */
    public function connect(ConnectionConfiguration $configuration, array $options = array())
    {
        try {
            return new PDO(
                $configuration->getDSN(),
                $configuration->getUsername(),
                $configuration->getPassword(),
                $options
            );
        } catch (PDOException $exception) {
            throw new ConnectionException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
