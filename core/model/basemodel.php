<?php
namespace Core\Model;

use PDO;
use Core\Model\AppDB;

/**
 * @description
 * Base class to connect to DB
 */
class BaseModel
{
    public PDO $conn;
    public int $limit = 30;

    public function __construct()
    {
        $this->conn = AppDB::getInstance()->getConnection();
    }
}