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

use Config\System as Config;
use Mobicms\Checkpoint\Facade;
use Zend\Http\PhpEnvironment\Request;

/**
 * Class Locales
 *
 * @package Mobicms\i18n
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-07-09
 */
class Locales
{
    /**
     * @var Request
     */
    private $request;

    private $availableLocales;
    private $sysConfig;
    private $userLocale;

    public function __construct(Request $request, Facade $user)
    {
        $this->request = $request;
        $this->sysConfig = \App::getInstance()->config()->get('lng');
        $this->userLocale = $user->get()->getConfig()->lng;
    }

    /**
     * Receive the list of languages together with names and flags
     *
     * @return array Locale => (html) Language name with Flag
     */
    public function getLocalesList()
    {
        $list = $this->getLocalesDescriptions();

        if (!array_key_exists('en', $list)) {
            $list['en'] = 'English';
        }

        ksort($list);

        return $list;
    }

    /**
     * Automatic detection of language
     *
     * @return string
     */
    protected function getCurrentLocale($session)
    {
        if (!$this->sysConfig['lngSwitch']) {
            return $this->sysConfig['lng'];
        }

        if (isset($session->lng)) {
            return $session->lng;
        }

        $locale = $this->getUserLocale();
        $session->lng = $locale;

        return $locale;
    }

    /**
     * Get the user-selected language
     *
     * @return string
     */
    private function getUserLocale()
    {
        if ($this->userLocale != '#' && in_array($this->userLocale, $this->getAvailableLocales())) {
            return $this->userLocale;
        }

        return $this->getBrowserLocale();
    }

    /**
     * Detect language by browser headers
     *
     * @return string
     */
    private function getBrowserLocale()
    {
        $locales = $this->request->getHeaders('Accept-Language')->getPrioritized();

        foreach ($locales as $lng) {
            if (in_array($lng->type, $this->getAvailableLocales())) {
                return $lng->type;
            }
        }

        return $this->sysConfig['lng'];
    }

    /**
     * Read descriptions fom .ini
     *
     * @return array
     */
    private function getLocalesDescriptions()
    {
        $description = [];
        $list = $this->getAvailableLocales();

        foreach ($list as $iso) {
            $file = LOCALE_PATH . $iso . DS . 'lng.ini';

            if (is_file($file) && ($desc = parse_ini_file($file)) !== false) {
                $description[$iso] = $this->getFlag($iso) . $desc['name'];
            }
        }

        return $description;
    }

    /**
     * Receive the list of available locales
     *
     * @return array
     */
    private function getAvailableLocales()
    {
        if ($this->availableLocales === null) {
            $list = glob(LOCALE_PATH . '*', GLOB_ONLYDIR);

            foreach ($list as $val) {
                $this->availableLocales[] = basename($val);
            }
        }

        return $this->availableLocales;
    }

    /**
     * Get language Flag
     *
     * @param string $locale
     * @return string
     */
    private function getFlag($locale)
    {
        $file = LOCALE_PATH . $locale . DS . 'lng.png';
        $flag = is_file($file) ? 'data:image/png;base64,' . base64_encode(file_get_contents($file)) : '';

        return $flag !== false ? '<img src="' . $flag . '" style="margin-right: 8px; vertical-align: middle">' : '';
    }
}
