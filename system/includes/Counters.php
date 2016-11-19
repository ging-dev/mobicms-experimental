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

namespace Includes;

use App;

//TODO: Удалить и заменить на новую систему
class Counters
{
    /**
     * @var int Зарегистрированные пользователи
     */
    public $users = 0;

    /**
     * @var int Новые зарегистрированные пользователи
     */
    public $users_new = 0;

    /**
     * @var int Пользовательские альбомы
     */
    public $album = 0;

    /**
     * @var int Пользовательские фотографии
     */
    public $album_photo = 0;

    /**
     * @var int Новые пользовательские фотографии
     */
    public $album_photo_new = 0;

    /**
     * @var int Счетчик файлов в Загруз-центре
     */
    public $downloads = 0;

    /**
     * @var int Счетчик файлов в Загруз-центре
     */
    public $downloads_mod = 0;

    /**
     * @var int Счетчик новых файлов в Загруз-центре
     */
    public $downloads_new = 0;

    /**
     * @var int Счетчик топиков Форума
     */
    public $forum_topics;

    /**
     * @var int Счетчик постов Форума
     */
    public $forum_messages = 0;

    /**
     * @var int Счетчик статей Библиотеки
     */
    public $library = 0;

    /**
     * @var int Счетчик новых статей Библиотеки
     */
    public $library_new = 0;

    /**
     * @var int Счетчик статей Библиотеки, находящихся на модерации
     */
    public $library_mod = 0;

    private $cache_file = 'counters.cache';
    private $update_cache = false;

    function __construct()
    {
        $count = $this->_cacheRead();
        $this->users = $this->_users($count['1']);
        $this->users_new = $this->_usersNew($count['2']);
        //$this->album = $this->_album($count['3']);
        //$this->album_photo = $this->_albumPhoto($count['4']);
        //$this->album_photo_new = $this->_albumPhotoNew($count['5']);
        //$this->downloads = $this->_downloads($count['6']);
        //$this->downloads_new = $this->_downloadsNew($count['7']);
        //$this->forum_topics = $this->_forumTopics($count['8']);
        //$this->forum_messages = $this->_forumMessages($count['9']);
        //$this->library = $this->_library($count['10']);
        //$this->library_new = $this->_libraryNew($count['11']);
        //$this->library_mod = $this->_libraryMod($count['12']);
        //$this->downloads_mod = $this->_downloadsMod($count['17']);

        if ($this->update_cache) {
            $this->_cacheWrite($count);
        }
    }

    /**
     * Счетчик посетителей Онлайн
     *
     * @return integer
     */
    public static function usersOnline()
    {
        return \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `users` WHERE `lastVisit` > " . (time() - 300))->fetchColumn();
    }

    /**
     * Счетчик гостей Онлайн
     *
     * @return integer
     */
    public static function guestsOnline()
    {
        return \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `sessions` WHERE `userId` = 0 AND `timestamp` > " . (time() - 300))->fetchColumn();
    }

    /**
     * Считываем данные из Кэша
     *
     * @return array|bool
     */
    private function _cacheRead()
    {
        $out = [];
        $file = CACHE_PATH . $this->cache_file;
        if (file_exists($file)) {
            $in = fopen($file, "r");
            while ($block = fread($in, 10)) {
                $tmp = unpack('Skey/Lcount/Ltime', $block);
                $out[$tmp['key']] = [
                    'count' => $tmp['count'],
                    'time'  => $tmp['time']
                ];
            }
            fclose($in);

            return $out;
        }

        return false;
    }

    /**
     * Записываем данные в Кэш
     *
     * @param array $data
     */
    private function _cacheWrite(array $data = [])
    {
        $file = CACHE_PATH . $this->cache_file;
        $in = fopen($file, "w+");
        flock($in, LOCK_EX);
        ftruncate($in, 0);
        foreach ($data as $key => $val) {
            fwrite($in, pack('SLL', $key, $val['count'], $val['time']));
        }
        fclose($in);
    }

    /**
     * Счетчик зарегистрированных пользователей сайта
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _users(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `users` WHERE `level` > 0")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик новых зарегистрированных пользователей сайта (за 1 день)
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _usersNew(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `users` WHERE `joinDate` > " . (time() - 86400))->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик Фотоальбомов
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _album(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 3600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(DISTINCT `user_id`) FROM `album__files`")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик картинок
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _albumPhoto(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 3600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `album__files`")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик новых картинок
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _albumPhotoNew(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `album__files` WHERE `time` > " . (time() - 259200) . " AND `access` > 1")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик файлов в загруз-центре
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _downloads(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 3600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `download__files` WHERE `type` = 2")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик новых файлов в загруз-центре
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _downloadsNew(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 3600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `download__files` WHERE `type` = 2 AND `time` > " . (time() - 259200))->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик файлов на модерации в загруз-центре
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _downloadsMod(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `download__files` WHERE `type` = 3")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик топиков Форума
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _forumTopics(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `forum__` WHERE `type` = 't' AND `close` != '1'")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик постов Форума
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _forumMessages(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `forum__` WHERE `type` = 'm' AND `close` != '1'")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик непрочитанных топиков Форума
     *
     * @return bool|integer
     */
    public static function forumMessagesNew()
    {
        $app = App::getInstance();

        if (!$app->user()->isValid()) {
            return false;
        }

        $user = $app->user()->get();
        return \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `forum__`
                LEFT JOIN `forum__rdm` ON `forum__`.`id` = `forum__rdm`.`topic_id` AND `forum__rdm`.`user_id` = '" . $user->id . "'
                WHERE `forum__`.`type`='t'" . ($user->rights >= 7 ? "" : " AND `forum__`.`close` != '1'") . "
                AND (`forum__rdm`.`topic_id` IS NULL
                OR `forum__`.`time` > `forum__rdm`.`time`)")->fetchColumn();
    }

    /**
     * Счетчик статей в Библиотеке
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _library(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 3600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `library` WHERE `type` = 'bk' AND `moder` = '1'")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик новых статей в Библиотеке (за 2 дня)
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _libraryNew(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 3600) {
            $this->update_cache = true;
            $var['count'] = \App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `library` WHERE `time` > '" . (time() - 259200) . "' AND `type` = 'bk' AND `moder` = '1'")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }

    /**
     * Счетчик статей на модерации в Библиотеке
     *
     * @param integer $var
     *
     * @return integer
     */
    private function _libraryMod(&$var)
    {
        if (!isset($var) || $var['time'] < time() - 600) {
            $this->update_cache = true;
            $var['count'] = App::getContainer()->get(\PDO::class)->query("SELECT COUNT(*) FROM `library` WHERE `type` = 'bk' AND `moder` = '0'")->fetchColumn();
            $var['time'] = time();
        }

        return $var['count'];
    }
}
