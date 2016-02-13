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
    private $snapCache = 'scanner.cache';
    private $snapFiles = [];

    public $folders = [];
    public $whiteList = [];
    public $excludedFolders = [];
    public $excludedFiles = [];

    public $newFiles = [];
    public $modifiedFiles = [];
    public $missingFiles = [];

    public function __construct()
    {
        $this->folders = Config\ScannerDefs::$folders;
        $this->whiteList = Config\ScannerDefs::$snap;
        $this->excludedFolders = Config\ScannerDefs::$excludedFolders;
        $this->excludedFiles = Config\ScannerDefs::$excludedFiles;
    }

    /**
     * Сканирование
     */
    public function scan($snap = false)
    {
        // Загружаем конфигурацию из снимка
        if ($snap) {
            $this->whiteList = [];
            if (file_exists(CACHE_PATH . $this->snapCache)) {
                include_once CACHE_PATH . $this->snapCache;

                if (isset($folders)) {
                    $this->folders = $folders;
                }

                if (isset($snap)) {
                    $this->whiteList = $snap;
                }
            }
        }

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

        file_put_contents(CACHE_PATH . $this->snapCache,
            '<?php' . "\n" .
            '$folders = ' . var_export($this->folders, true) . ';' . "\n" .
            '$snap = ' . var_export($filecontents, true) . ';' . "\n"
        );
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
                        $file_date = date("d.m.Y H:i:s", filemtime($dir . '/' . $file) + \Config\System::$timeshift * 3600);

                        if ($snap) {
                            $this->snapFiles[] = [
                                'file_path' => $folder . '/' . $file,
                                'file_crc'  => $file_crc
                            ];
                        } else {
                            // Проверяем наличие новых файлов
                            if (!array_key_exists($folder . '/' . $file, $this->whiteList)) {
                                $this->newFiles[] = [
                                    'file_path' => $folder . '/' . $file,
                                    'file_date' => $file_date
                                ];
                                // Проверяем несоответствие контрольным суммам
                            } elseif ($this->whiteList[$folder . '/' . $file] != $file_crc && !in_array($folder . '/' . $file, $this->excludedFiles)) {
                                $this->modifiedFiles[] = [
                                    'file_path' => $folder . '/' . $file,
                                    'file_date' => $file_date
                                ];
                            }
                        }
                    }
                }
            }
        }
    }
}