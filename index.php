<?php

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
if (version_compare(PHP_VERSION, '5.6', '<')) {
    die('<div style="text-align: center; font-size: xx-large"><strong>ERROR!</strong><br>Your needs PHP 5.6 or higher</div>');
}

/**
 * Bootstrap the application
 */
require __DIR__ . '/system/bootstrap.php';
App::getContainer()->get(Mobicms\Api\RouterInterface::class)->dispatch();
