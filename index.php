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
define('JOHNCMS', '8.0.0-alpha1');

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
