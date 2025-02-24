<?php
namespace Core\Model;

use PDO;
use Core\Model\AppDB;

/**
 * @description Model for work with table "transactions"
 */
class UserTransactionModel extends BaseModel
{
    /**
     * @description function get calculated balance
     */
    public function getUserTransactionsBalances(int $userId = 0) : array 
    {
        //@todo add after exceptions for component
        if ($userId <= 0 ) 
        { 
            return [
                'success' => false,
                'message' => 'User not specified'
            ]; 
        }

        $statement = $this->conn->prepare("SELECT id FROM user_accounts WHERE user_id = ? LIMIT $this->limit OFFSET 0");
        $statement->execute([$userId]);
        $accounts = $statement->fetchAll(PDO::FETCH_COLUMN, 0);

        if (empty($accounts)) { return []; }
        
        $accountsList = implode(',', $accounts);
        //table `transactions` 
        //fields (`id`,`account_from`,`account_to`,`amount`,`trdate`)
        //format dateTime 2024-01-30 12:00:00
        $query = "
            SELECT 
                strftime('%m', trdate) AS month,
                SUM(CASE WHEN account_from IN ($accountsList) AND account_to NOT IN ($accountsList) THEN amount ELSE 0 END) AS outgoing,
                SUM(CASE WHEN account_to IN ($accountsList) AND account_from NOT IN ($accountsList) THEN amount ELSE 0 END) AS incoming
            FROM transactions
            WHERE (account_from IN ($accountsList) OR account_to IN ($accountsList))
            GROUP BY month
            ORDER BY month
        ";
        $statement = $this->conn->query($query);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        //pr($results, 1); //@todo for debug

        $balances = [];
        foreach ($results as $row) 
        {
            $balance = $row['incoming'] - $row['outgoing'];
            $balances[$row['month']] = $balance;
        }

        return $balances;
    }

    private function getAllTransactionsDebugData() : void
    {
        $query = "SELECT * FROM transactions";
        $statement = $this->conn->query($query);
        $test = $statement->fetchAll(PDO::FETCH_ASSOC);

        pr($test, 1);
    }
}