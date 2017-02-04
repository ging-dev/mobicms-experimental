<?php

defined('MOBICMS') or die('Error: restricted access');

/** @var Mobicms\Api\ViewInterface $view */
$view = App::getContainer()->get(Mobicms\Api\ViewInterface::class);

$view->setTemplate('option_avatar.php');
