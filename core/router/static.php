<?php

/**
 * @description
 * file checker router for static files
 * if file exist open file
 * else work Routing
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($uri !== '/' && file_exists($_SERVER['DOCUMENT_ROOT'] . $uri))
{
    return false;
}

include $_SERVER['DOCUMENT_ROOT'] . '/index.php';
