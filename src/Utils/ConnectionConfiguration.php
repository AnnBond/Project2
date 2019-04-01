<?php

namespace Console\Utils;

class ConnectionConfiguration
{
    /**
     * @var string
     */
    protected $db;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var array
     */
    protected $options;

    /**
     * Connection configuration constructor.
     *
     * @param string $db
     * @param string $username
     * @param string $password
     * @param string $host
     * @param int $port
     */
    public function __construct(
        string $db,
        string $username,
        ?string $password,
        string $host,
        int $port
    ) {
        $this->db = $db;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get PDO DSN
     *
     * @return string
     */
    public function getDSN(): string
    {
        return sprintf("mysql:host=%s:%s;dbname=%s;charset=utf8", $this->host, $this->port, $this->db);
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
