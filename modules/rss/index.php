<?php

defined('MOBICMS') or die('Error: restricted access');

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
$feed->setGenerator('mobiCMS', MOBICMS, 'http://mobicms.net');
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
