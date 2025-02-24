<?php
namespace Core\Model;

use PDO;

/**
 * @description Model for work with table users
 */
class UserModel extends BaseModel
{
    /**
     * @description function get all users
     */
    public function getUsers() : array
    {
        ;
        $select = $this->conn->query("SELECT id, name FROM users LIMIT $this->limit OFFSET 0");
        return $select->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}