<?php
namespace Core\Controller;

use Core\Model;

/**
 * @description controller user transaction
 */
class UserTransactions
{
    protected string $action = '';
    public int $userAction = 0;
    public bool $isAjax = false;

    /**
    * @description action handler controller
    */
    public function doAction() : array
    {
        $result = [];

        $headers = getallheaders();
        $this->isAjax = !empty($headers['x-requested-with']) && strtolower($headers['x-requested-with']) === 'xmlhttprequest';
        $this->action = $_REQUEST['action'] ?? 'form';
        $this->userAction = $_REQUEST['user'] ?? 0;
        if ($this->isAjax && $this->action === 'transactions' && $this->userAction)
        {
            $result = $this->getTransactions($this->userAction);
        }

        return $result;
    }

    /**
    * @description Generating a controller response
    */
    public function getTransactions(int $userId = 0) : array 
    {
        if (!$userId) 
        {
            return [
                'success' => false,
                'message' => 'User not specified'
            ];
        }

        $transactions = (new Model\UserTransactionModel())->getUserTransactionsBalances($userId);

        if (empty($transactions))
        {
            return [
                'success' => false,
                'user' => $this->userAction,
                'message' => 'No found transactions'
            ];
        }

        return [
            'success' => true,
            'user' => $this->userAction,
            'result' => $transactions
        ];
    }
}