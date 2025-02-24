<?php
namespace Core\View\Component;

use Core\Model;
use Core\Controller;

/**
 * @description Component form and result
 */
class UserTransactionsInfo 
{
    private array $users = [];
    private string $usersOptions = '';
    private ?Controller\UserTransactions $controller = null;
    private $responseData = [];

    public function __construct() 
    {
        $userModel = new Model\UserModel();
        $this->users = $userModel->getUsers();

        if ($this->users)
        {
            $this->controller = new Controller\UserTransactions();
            $this->responseData = $this->controller->doAction();
        }

        $this->collectUsers();
    }

    public function collectUsers(): void 
    {
        $html = '';
        foreach ($this->users as $id => $name)
        {
            if ($this->controller)
            {
                $selected = $this->controller->userAction === $id ? 'selected' : '';
            }
            $html .= sprintf('<option value="%d" %s>%s</option>', $id, $selected, htmlspecialchars($name));
        }

        $this->usersOptions = $html;
    }

    /**
     * @description render component
     */
    public function render(): string 
    {
        $html = '';
        
        ob_start();

        $options = $this->usersOptions;
        if (empty($options))
        {
            echo <<<HTML
<p>Users not found</p>
HTML;
        }
        else
        {
            $actionForm = '/';
            $method = 'get';
            $responseHtml = '';
            if ($this->responseData)
            {
                $responseHtml = $this->renderResponseData();
            }
            
            echo <<<HTML
<form id="formUserTransaction" action="{$actionForm}" method="{$method}">
    <input type="hidden" name="action" value="transactions">
    <label for="user">Select user:</label>
    <select name="user" id="user">{$options}</select>    
    <input id="submit" type="submit" value="Show">
</form>
<div id="data">
    {$responseHtml}
</div>
HTML;
        }

        $html = ob_get_clean();

        if ($this->controller && $this->controller->isAjax)
        {
            // pr($this,1); pr($responseHtml,1); die();
            ob_start();
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            header("Content-Type: text/html; charset=UTF-8");

            echo $responseHtml;
            die();
        }

        return $html;
    }

    /**
     * @description render response controller
     */
    public function renderResponseData()
    {
        $userName = $this->users[$this->responseData['user']];

        $lang = match (trim(mb_strtolower($GLOBALS['LANG'])))
        {
            'en' => 'en_US',
            'de' => 'de-DE',
            default => 'ru_RU',
        };
    
        $formatter = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ];
        
        if (class_exists('IntlDateFormatter')) 
        {
            $formatter = new \IntlDateFormatter($lang, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'Europe/Moscow', \IntlDateFormatter::GREGORIAN, 'LLLL');
        }

        $responseHtml = '<table>';
        $responseHtml .= sprintf('<tr><th>%s</th><th>%d</th>', 'Mounth', 'Amount');
        
        if ($this->responseData['success'] && !empty($this->responseData['result']))
        {
            foreach ($this->responseData['result'] as $month => $value)
            {
                $monthFormat = is_object($formatter) ? $formatter->format(mktime(0, 0, 0, $month, 1)) : $formatter[$month];
                $responseHtml .= sprintf('<tr><td>%s</td><td>%d</td>', $monthFormat, $value);
            }
        }
        else
        {
            $responseHtml .= '<tr><td>No user data found</td><td>-</td></tr>';
        }

        $responseHtml .= '</table>';

        return <<<HTML
    <h2>Transactions of {$userName}</h2>
    {$responseHtml}
HTML;
    }
}