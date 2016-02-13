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

namespace Mobicms\i18n;

use Mobicms\Checkpoint\Facade;
use RecursiveDirectoryIterator as DirIterator;
use RecursiveIteratorIterator as Iterator;
use Mobicms\i18n\Plural\Pluralization;
use Mobicms\i18n\Loader\GettextPo;
use Zend\Http\PhpEnvironment\Request;
use Zend\Session\Container as Session;

/**
 * Class Translate
 *
 * @package Mobicms\i18n
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.2.1.0 2015-09-17
 */
class Translate extends Locales
{
    /**
     * @var \ArrayObject Instances of loaded Domains
     */
    private $domains = [];

    private $module;
    private $currentLocale;
    private $cachePath;

    public function __construct(Request $request, Session $session, Facade $user)
    {
        parent::__construct($request, $user);
        // Get user defined locale
        $this->currentLocale = $this->getCurrentLocale($session);
        $this->cachePath = CACHE_PATH . 'locale';
    }

    /**
     * Translate a message
     *
     * @param string $message
     * @param string $domain
     * @return string
     */
    public function translateSystem($message, $domain = 'default')
    {
        return $this->getMessage($message, $domain, 'system');
    }

    /**
     * The plural version of translate()
     *
     * @param string $singular
     * @param string $plural
     * @param int    $count
     * @param string $domain
     * @return string
     */
    public function translateSystemPlural($singular, $plural, $count, $domain = 'default')
    {
        return $this->getPluralMessage($singular, $plural, $count, $domain, 'system');
    }

    /**
     * Translate a message with override the current domain
     *
     * @param string $message
     * @param string $domain
     * @return string
     */
    public function translateModule($message, $domain = 'default')
    {
        return $this->getMessage($message, $domain, $this->module);
    }

    /**
     * Plural version of $this->dgettext();
     *
     * @param string $singular
     * @param string $plural
     * @param int    $count
     * @param string $domain
     * @return string
     */
    public function translateModulePlural($singular, $plural, $count, $domain = 'default')
    {
        return $this->getPluralMessage($singular, $plural, $count, $domain, $this->module);
    }

    /**
     * Set module
     *
     * @param $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * Get Domain
     *
     * @param $domain
     * @return \ArrayObject
     */
    public function getDomain($domain, $module)
    {
        if (!isset($this->domains[$module][$domain])) {
            $cacheFile = $this->cachePath . DS . $this->currentLocale . '.' . $module . '.' . $domain . '.cache';

            if (!is_file($cacheFile)) {
                $this->writeCache($cacheFile, $domain, $module);
            }

            $this->domains[$module][$domain] = new \ArrayObject(include $cacheFile);
        }

        return $this->domains[$module][$domain];
    }

    /**
     * Clear locales cache
     */
    public function clearCache()
    {
        if (is_dir($this->cachePath)) {
            $scan = new Iterator(new DirIterator($this->cachePath, DirIterator::SKIP_DOTS), Iterator::CHILD_FIRST);

            foreach ($scan as $fileinfo) {
                $action = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $action($fileinfo->getRealPath());
            }

            rmdir($this->cachePath);
        }
    }

    /**
     * Write locales cache
     *
     * @param string $cacheFile
     * @param string $domain
     */
    private function writeCache($cacheFile, $domain, $module)
    {
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath);
        }

        if ($module == 'system') {
            $path = LOCALE_PATH;
        } else {
            $path = MODULE_PATH . $module . DS . 'locale' . DS;
        }

        $poFile = $path . $this->currentLocale . DS . $domain . '.po';
        file_put_contents($cacheFile, '<?php' . PHP_EOL . 'return ' . var_export((new GettextPo)->parse($poFile), true) . ';' . PHP_EOL);
    }

    /**
     * Get translated message
     *
     * @param string $message
     * @param string $domain
     * @return string
     */
    private function getMessage($message, $domain, $module)
    {
        $msgObj = $this->getDomain($domain, $module);

        return $msgObj->offsetExists($message) ? $msgObj->offsetGet($message) : $message;
    }

    /**
     * Plural version of $this->getMessage()
     *
     * @param string $singular
     * @param string $plural
     * @param int    $count
     * @param string $domain
     * @return string
     */
    private function getPluralMessage($singular, $plural, $count, $domain, $module)
    {
        $msgObj = $this->getDomain($domain, $module);

        if (!$msgObj->offsetExists($plural)) {
            return ($count != 1) ? $plural : $singular;
        }

        $list = explode(chr(0), $msgObj->offsetGet($plural));

        return $list[Pluralization::get($count, $this->currentLocale)];
    }
}
