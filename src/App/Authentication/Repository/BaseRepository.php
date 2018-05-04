<?php


namespace App\Authentication\Repository;


use mysqli;

class BaseRepository
{
    /**
     * @var mysqli
     */
    protected $conn;

    /**
     * BaseRepository constructor.
     * @param mysqli $connection
     */
    public function __construct(mysqli $connection)
    {
        $this->conn = $connection;
    }

}