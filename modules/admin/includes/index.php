<?php

defined('MOBICMS') or die('Error: restricted access');

App::getContainer()->get(Mobicms\Api\ViewInterface::class)->setTemplate('index.php');
