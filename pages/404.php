<?php if (!defined("PROLOG_INCLUDED") || PROLOG_INCLUDED!==true) { die(); }

http_response_code(404);
$title = 'Error 404';
Core\View\Page::$title = $title;
?>
<h1><?=$title?></h1>
<p style="text-align: center;">Sorry! The requested page does not exist.</p>
