<?php
/*
 * mobiCMS Content Management System (http://mobicms.net)
 *
 * For copyright and license information, please see the LICENSE.md
 * Installing the system or redistributions of files must retain the above copyright notice.
 *
 * @link        http://mobicms.net mobiCMS Project
 * @copyright   Copyright (C) mobiCMS Community
 * @license     LICENSE.md (see attached file)
 */

/**
 * System version
 */
define('MOBICMS', '0.1.0');

/**
 * Toggle debug mode
 */
define('DEBUG', true);

/**
 * Check the current PHP version
 */
if (version_compare(PHP_VERSION, '5.5', '<')) {
    die('<div style="text-align: center; font-size: xx-large"><strong>ERROR!</strong><br>Your needs PHP 5.5 or higher</div>');
}

/**
 * Bootstrap the application
 */
require __DIR__ . '/system/bootstrap.php';
App::getInstance()->router()->dispatch();

// Fetch the latest Slashdot headlines
//try {
//    $rss = Zend\Feed\Reader\Reader::import('http://www.3dnews.ru/news/rss/');
//    $rss = Zend\Feed\Reader\Reader::import('http://4pda.ru/feed/');
//} catch (Zend\Feed\Reader\Exception\RuntimeException $e) {
//    // feed import failed
//    echo "Exception caught importing feed: {$e->getMessage()}\n";
//    exit;
//}

//echo '<div class="content box padding">';

//echo '<h3><a href="' . $rss->getLink() . '">' . $rss->getTitle() . '</a></h3>';

/**
 * @var $val Zend\Feed\Reader\Feed\Rss
 */
//foreach ($rss as $val) {
//    echo '<div class="alert alert-neytral">';
//    echo '<h4><a href="' . $val->getLink() . '" target="_blanc">' . $val->getTitle() . '</a></h4>';
//    echo '<p>' . $val->getDescription() . '</p>';
//    echo '</div>';
//}
//
//echo '</div>';
