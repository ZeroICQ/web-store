<?php

namespace App\ORM;


use App\Authentication\Repository\UserRepository;
use mysqli;

class EntityManager
{
    /**
     * @var mysqli
     */
    private $conn;

    /**
     * @var  array
     */
    private $repos;

    /**
     * EntityManager constructor.
     * @param mysqli $connection
     */
    public function __construct(mysqli $connection)
    {
        $this->conn = $connection;
        //register existing repositories
        $this->repos['user'] = UserRepository::class;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getRepository(string $name)
    {
        $repo = new $this->repos[$name]($this->conn);
        return $repo;
    }

}