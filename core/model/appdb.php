<?php
namespace Core\Model;

use PDO;

/**
 * @description
 * Base instance class for connecting to the database
 */

class AppDB
{
  private static ?AppDB $instance = null;

  private \PDO $connection;

  protected function __clone() { }

  public function __wakeup()
  {
      throw new \Exception("Cannot unserialize a singleton.");
  }

  protected function __construct()
	{
    try
    {
      $this->connection = new PDO("sqlite:" . __DIR__ . "/database.sqlite");
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    catch (PDOException $e) 
    {
        die("Ошибка подключения к БД: " . $e->getMessage());
    }
  }

  public static function getInstance(): AppDB
  {
    if (self::$instance === null) 
    {
        self::$instance = new AppDB();
    }

    return self::$instance;
  }

  public function getConnection(): \PDO 
  {
    return $this->connection;
  }

  public function initDB() : void
  {
    $sqlCreate = "
      CREATE TABLE IF NOT EXISTS users (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          name TEXT NOT NULL
      );

      CREATE TABLE IF NOT EXISTS user_accounts (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          user_id INTEGER NOT NULL,
          FOREIGN KEY (user_id) REFERENCES users(id)
      );

      CREATE TABLE IF NOT EXISTS transactions (
          id INTEGER PRIMARY KEY AUTOINCREMENT,
          account_from INTEGER NOT NULL,
          account_to INTEGER NOT NULL,
          amount REAL NOT NULL,
          trdate TEXT NOT NULL,
          FOREIGN KEY (account_from) REFERENCES user_accounts(id),
          FOREIGN KEY (account_to) REFERENCES user_accounts(id)
      );
    ";

    $this->connection->exec($sqlCreate);
    $this->connection->exec("DELETE FROM `transactions`");
    $this->connection->exec("DELETE FROM `user_accounts`");
    $this->connection->exec("DELETE FROM `users`");

    $sqlInsert = "
      INSERT INTO `users` (`id`,`name`)
      VALUES
        (10, 'Alice'),
        (11, 'Bob'),
        (12, 'Tom'),
        (13, 'Mike'),
        (14, 'Kate'),
        (15, 'Jerry');
      
      INSERT INTO `user_accounts` (`id`,`user_id`)
      VALUES
        (10, 10),
        (11, 10),
        (12, 11),
        (13, 11),
        (14, 12),
        (15, 12),
        (16, 13),
        (17, 14),
        (18, 15);
      
      INSERT INTO `transactions` (`id`,`account_from`,`account_to`,`amount`,`trdate`)
      VALUES
        (1, 10, 11, 100.00, '2024-01-01 12:00:00'),
        (2, 11, 10, 50.00, '2024-01-05 12:00:00'),
        (3, 12, 10, 100.00, '2024-01-10 12:00:00'),
        (4, 13, 10, 100.00, '2024-01-15 12:00:00'),
        (5, 14, 10, 100.00, '2024-01-20 12:00:00'),
        (6, 15, 12, 100.00, '2024-01-25 12:00:00'),
        (7, 13, 12, 100.00, '2024-01-30 12:00:00'),
        (8, 11, 15, 50.00, '2024-02-05 12:00:00'),
        (9, 12, 10, 100.00, '2024-02-10 12:00:00'),
        (10, 13, 10, 200.00, '2024-02-15 12:00:00'),
        (11, 14, 11, 50.00, '2024-02-20 12:00:00'),
        (12, 11, 10, 100.00, '2024-02-25 12:00:00'),
        (13, 14, 11, 100.00, '2024-03-05 12:00:00'),
        (14, 12, 10, 100.00, '2024-03-10 12:00:00'),
        (15, 12, 10, 100.00, '2024-03-15 12:00:00'),
        (16, 11, 10, 100.00, '2024-03-20 12:00:00'),
        (17, 10, 11, 50.00, '2024-03-25 12:00:00');
    ";
    $this->connection->exec($sqlInsert);
  }
}