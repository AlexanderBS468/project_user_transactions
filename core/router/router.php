<?php
namespace Core\Router;

use Core\Model;
use Core\View;

class Router
{
    public static string $content = '';
    
    /**
     * @description function check and include file page
     * Routing page files are located in '/pages/’
     */
	public static function dispatch($url) : void
	{
        $request_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        if ($request_uri === '') {
            $request_uri = 'index.php';
        }

        $file = $_SERVER['DOCUMENT_ROOT'] . '/pages/' . $request_uri;
        
        if (file_exists($file)) 
        {
            ob_start();
            include $file;
            $content = ob_get_clean();
            View\Page::contentBody($content);
        }
        else 
        {
            include __DIR__ . '/../../pages/404.php';
        }

        View\Page::render();
	}
}
