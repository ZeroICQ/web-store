<?php


namespace App\Authentication\Repository;


use App\ORM\DB;
use mysqli;

class BaseRepository
{
    /**
     * @var DB
     */
    protected $db;

    /**
     * BaseRepository constructor.
     * @param DB $db
     */
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

}