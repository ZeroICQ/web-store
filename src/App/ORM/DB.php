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
        $stmt = $this->connection->prepare("SELECT  {$selectFields} FROM {$table} WHERE {$searchField} = ?");
        $stmt->bind_param($valType, $val);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result;
    }

    /**
     * @param string $table
     * @param array $fields
     * @param string $searchField
     * @param $val
     * @param string $valType
     * @param string $joinTable
     * @param array $joinCond
     * @param array $joinFields
     * @return array
     */
//    public function selectFirstWithSimpleJoin(string $table, array $fields, string $searchField, $val, string $valType,
//                                              string $joinTable, array $joinCond, array $joinFields)
//    {
//        $selectFields = implode(', ', array_map(function ($val) use ($table) {return $table.".".$val; }, $fields));
//        $selectJoinFields = implode(', ', array_map(function ($val) use ($joinTable) {return $joinTable.".".$val; }, $joinFields));
//        $query = "SELECT  {$selectFields}, {$selectJoinFields} "
//                  ."FROM {$table} "
//                  ."LEFT JOIN {$joinTable} ON {$table}.{$joinCond[0]} = {$joinTable}.{$joinCond[1]} "
//                  ."WHERE ${table}.{$searchField} = ?";
//
//        $stmt = $this->connection->prepare($query);
//        $stmt->bind_param($valType, $val);
//        $stmt->execute();
//
//        $result = $stmt->get_result()->fetch_assoc();
//        $stmt->close();
//
//        return $result;
//    }

    /**
     * @param string $table
     * @param array $fields
     * @param string $valTypes
     * @return int
     */
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

    /**
     * @param string $table
     * @param array $fields
     * @param string $searchField
     * @param $val
     * @param $valTypes
     * @return bool
     */
    public function update(string $table, array $fields, string $searchField, $val, $valTypes): bool
    {
        $updateFields = implode(', ', array_map(function ($uVal) {return "{$uVal} = ?";}, array_keys($fields)));
        $updateValues = array_values($fields);

        $stmt = $this->connection->prepare(
            "UPDATE {$table} SET $updateFields WHERE {$searchField} = ?");

        array_push($updateValues, $val);
        $stmt->bind_param($valTypes, ...$updateValues);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
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
