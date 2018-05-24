<?php


namespace App\ORM;


use mysqli;

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
     * @var mysqli
     */
    private $connection;

    /**
     * DB constructor.
     * @param string $host
     * @param string $username
     * @param string $passwd
     * @param string $dbname
     * @param int $port
     */
    public function __construct(string $host, string $username, string $passwd, string $dbname, int $port = 3306)
    {
        $this->host = $host;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->dbname = $dbname;
        $this->port = $port;
        $this->connection = new mysqli($host, $username, $passwd, $dbname, $port);
        }

    public function __destruct()
    {
        $this->connection->close();
    }


    /**
     * @param string $table
     * @param array $fields
     * @param string $searchField
     * @param $val
     * @param string $valType
     * @return array
     */
    public function selectFirstSimpleEqCond(string $table, array $fields, string $searchField, $val, string $valType)
    {
        $selectFields = implode(', ', $fields);
        $stmt = $this->connection->prepare("SELECT  {$selectFields} FROM ${table} WHERE {$searchField} = ?");
        $stmt->bind_param($valType, $val);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result;
    }

    public function insert(string $table, array $fields, string $valTypes): int
    {
        $insertFields = implode(',', array_keys($fields));
        $insertValues = array_values($fields);
        $questionMarks = implode(',', array_map(function () {return '?';}, $insertValues));

        $stmt = $this->connection->prepare("INSERT INTO {$table}($insertFields) values($questionMarks)");

        $stmt->bind_param($valTypes, ...$insertValues);
        $stmt->execute();

        $insert_id = $this->connection->insert_id;
        $stmt->close();

        return $insert_id;
    }

    public function startTransaction()
    {
        $this->connection->begin_transaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollback()
    {
        $this->connection->rollback();
    }
}
