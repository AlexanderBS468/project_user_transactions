<?php if (!defined("PROLOG_INCLUDED") || PROLOG_INCLUDED!==true) { die(); }

$title = 'User transactions information';
Core\View\Page::$title = $title;

?><h1><?=$title?></h1><?php

try
{
    echo (new Core\View\Component\UserTransactionsInfo())->render();
}
catch (\Exception | \Error $exc)
{
    pr('Exception ' . $exc->getMessage(), 1);
}

