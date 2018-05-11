<?php


namespace App\ORM;


class DB
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $passwd;
    /**
     * @var string
     */
    private $dbname;
    /**
     * @var int
     */
    private $port;

    /**
     * DB constructor.
     * @param string $host
     * @param string $username
     * @param string $passwd
     * @param string $dbname
     * @param $int
     */
    public function __construct(string $host, string $username, string $passwd, string $dbname, int $port = 3306)
    {
        $this->host = $host;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->dbname = $dbname;
        $this->port = $port;
    }

}