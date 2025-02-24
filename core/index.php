<?php
require_once '.env.php';
require_once 'autoloader.php';
require_once 'functions/index.php';

$GLOBALS['TEMPLATE_NAME'] = (defined('TEMPLATE_NAME') && TEMPLATE_NAME !== '') ? TEMPLATE_NAME : 'main';
$GLOBALS['LANG'] = isset($_REQUEST['lang']) && $_REQUEST['lang'] ? $_REQUEST['lang'] : 'ru';

define('PROLOG_INCLUDED', true);

Core\Router\Router::dispatch($_SERVER['REQUEST_URI']);
