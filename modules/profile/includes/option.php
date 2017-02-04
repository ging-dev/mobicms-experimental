<?php

defined('MOBICMS') or die('Error: restricted access');

/** @var Mobicms\Api\ViewInterface $view */
$view = App::getContainer()->get(Mobicms\Api\ViewInterface::class);

// Показываем меню настроек
$view->setTemplate('option.php');
