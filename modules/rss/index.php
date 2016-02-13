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

defined('MOBICMS') or die('Error: restricted access');

use Config\System as Config;

$app = App::getInstance();
$homeUrl = $app->request()->getBaseUrl();
$rssCacheFile = CACHE_PATH . 'rss-feed.cache';   // Cache file
$rssCacheTime = 600;                             // Cache Time in seconds

// Read the RSS feed from the database and write cache
if (!is_file($rssCacheFile)
    || filemtime($rssCacheFile) < time() - $rssCacheTime
) {
    $rss = [
        '<rss version="2.0">',
        '<channel>',
        '<title>' . htmlspecialchars(Config::$copyright) . '</title>',
        '<link>' . $homeUrl . '</link>',
        '<description>Site news</description>',
        '<language>ru-ru</language>',
        '<pubDate>' . date("D, j M Y G:i:s", time()) . ' GMT' . '</pubDate>',
        '<lastBuildDate>' . date("D, j M Y G:i:s", time()) . ' GMT' . '</lastBuildDate>',
        '<docs>http://blogs.law.harvard.edu/tech/rss</docs>',
        '<generator>mobiCMS http://mobicms.net</generator>',
        '<webMaster>' . Config::$email . '</webMaster>'
    ];

    $query = $app->db()->query("SELECT * FROM `news` ORDER BY `id` DESC LIMIT 15");

    while ($result = $query->fetch()) {
        $rss[] = '<item>';
        $rss[] = '<title><![CDATA[' . strip_tags(trim($result['title'])) . ']]></title>';
        $rss[] = '<link>' . $homeUrl . '/news/' . '</link>';
        $rss[] = '<description><![CDATA[' . strip_tags(trim($result['text'])) . ']]></description>';
        $rss[] = '<pubDate>' . date("D, j M Y G:i:s", $result['time']) . ' GMT' . '</pubDate>';
        $rss[] = '<guid>' . $homeUrl . '/news/' . '</guid>';
        $rss[] = '</item>';
    }

    $rss[] = '</channel>';
    $rss[] = '</rss>';

    // Write RSS cache
    if (file_put_contents($rssCacheFile, implode("\n", $rss)) === false) {
        throw new RuntimeException('Can not write RSS cache file');
    }
}

// Display RSS feed
ob_end_clean();
$app->view()->setLayout(false);
header('Content-type: text/xml; charset="utf-8"');
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
readfile($rssCacheFile);
