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
 * Class Scanner
 *
 * @package Mobicms\SecurityScanner
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-02-01
 */
class Scanner
{
    private $config;
    private $snapFiles = [];

    public $folders =
        [
            0 => '',
            1 => '/assets',
            3 => '/modules',
            4 => '/system',
            5 => '/themes',
            6 => '/uploads',
        ];
    public $whiteList = [];
    public $excludedFolders = [];
    public $excludedFiles =
        [
            './system/config/data/scan.php'
        ];

    public $newFiles = [];
    public $modifiedFiles = [];
    public $missingFiles = [];

    public function __construct()
    {
        $this->config = App::getInstance()->config();
    }

    /**
     * Сканирование
     */
    public function scan()
    {
        $this->whiteList = include_once CONFIG_FILE_SCAN;

        // Сканируем предмет наличия новых, или модифицированных файлов
        foreach ($this->folders as $dir) {
            $this->scanFiles(ROOT_DIR . $dir);
        }

        // Сканируем на предмет отсутствующих файлов
        foreach ($this->whiteList as $file => $crc) {
            if (!is_file($file)) {
                $this->missingFiles[] = $file;
            }
        }
    }

    /**
     * Добавляем снимок надежных файлов в базу
     */
    public function snap()
    {
        foreach ($this->folders as $data) {
            $this->scanFiles(ROOT_DIR . $data, true);
        }

        $filecontents = [];

        foreach ($this->snapFiles as $idx => $data) {
            $filecontents[$data['file_path']] = $data['file_crc'];
        }

        (new Zend\Config\Writer\PhpArray)->toFile(CONFIG_FILE_SCAN, $filecontents);
    }

    /**
     * Служебная функция рекурсивного сканирования файловой системы
     *
     * @param      $dir
     * @param bool $snap
     */
    private function scanFiles($dir, $snap = false)
    {
        if ($dh = @opendir($dir)) {
            while (false !== ($file = readdir($dh))) {
                if ($file == '.' || $file == '..') {
                    continue;
                }

                if (is_dir($dir . '/' . $file)) {
                    if ($dir != ROOT_DIR && !in_array($dir, $this->excludedFolders)) {
                        $this->scanFiles($dir . '/' . $file, $snap);
                    }
                } else {
                    if (preg_match("#.*\.(php|cgi|pl|perl|php3|php4|php5|php6|phtml|py|htaccess)$#i", $file)) {
                        $folder = str_replace("../..", ".", $dir);
                        $file_crc = strtoupper(dechex(crc32(file_get_contents($dir . '/' . $file))));
                        $file_date = date("d.m.Y H:i:s", filemtime($dir . '/' . $file) + $this->config['sys']['timeshift'] * 3600);

                        if ($snap) {
                            $this->snapFiles[] = [
                                'file_path' => $folder . '/' . $file,
                                'file_crc'  => $file_crc,
                            ];
                        } else {
                            // Проверяем наличие новых файлов
                            if (!array_key_exists($folder . '/' . $file, $this->whiteList)) {
                                $this->newFiles[] = [
                                    'file_path' => $folder . '/' . $file,
                                    'file_date' => $file_date,
                                ];
                                // Проверяем несоответствие контрольным суммам
                            } elseif ($this->whiteList[$folder . '/' . $file] != $file_crc && !in_array($folder . '/' . $file, $this->excludedFiles)) {
                                $this->modifiedFiles[] = [
                                    'file_path' => $folder . '/' . $file,
                                    'file_date' => $file_date,
                                ];
                            }
                        }
                    }
                }
            }
        }
    }
}
