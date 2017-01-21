<?php
/*
 * mobiCMS Content Management System (http://mobicms.net)
 *
 * For full copyright and license information, please see the LICENSE.md
 * Installing the system or redistributions of files must retain the above copyright notice.
 *
 * @link        http://mobicms.net mobiCMS Project
 * @copyright   Copyright (C) mobiCMS Community
 * @license     LICENSE.md (see attached file)
 *
 * @module      RSS
 * @author      Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version     v.1.0.0 2015-02-01
 */

defined('JOHNCMS') or die('Error: restricted access');

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var Mobicms\Api\ConfigInterface $config */
$config = $container->get(Mobicms\Api\ConfigInterface::class);

/** @var Mobicms\Api\ViewInterface $view */
$view = $container->get(Mobicms\Api\ViewInterface::class);

$app = App::getInstance();

/**
 * Create the parent feed
 */
$feed = new Zend\Feed\Writer\Feed;
$feed->setTitle($config->siteName);
$feed->setLink($config->homeUrl);
$feed->setFeedLink($config->homeUrl . '/rss', 'rss');
$feed->setGenerator('mobiCMS', JOHNCMS, 'http://mobicms.net');
$feed->setDateModified(time());
$feed->setDescription('mobiCMS news');

/**
 * Add one or more entries. Note that entries must
 * be manually added once created.
 */
$entry = $feed->createEntry();
$entry->setTitle('Тестовая новость');
$entry->setLink($config->homeUrl . '/news/423');
$entry->addAuthor(array(
    'name'  => 'admin',
    'email' => 'admin@example.com',
    'uri'   => 'http://www.example.com',
));
$entry->setDateModified(time());
$entry->setDateCreated(time());
$entry->setDescription('Проверка слуха.');
$feed->addEntry($entry);

$view->setLayout(false);
header('Content-type: text/xml; charset="utf-8"');
echo $feed->export('rss');
