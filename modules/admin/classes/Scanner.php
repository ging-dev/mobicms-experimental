<?php

/**
 * Class Scanner
 *
 * @package Mobicms\SecurityScanner
 * @author  Oleg Kasyanov <dev@mobicms.net>
 */
class Scanner
{
    /**
     * @var Mobicms\Api\ConfigInterface
     */
    private $config;
    private $snapFiles = [];
    private $cacheFile = 'scaner.cache';

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
    public $excludedFiles = [];

    public $newFiles = [];
    public $modifiedFiles = [];
    public $missingFiles = [];

    public function __construct()
    {
        $this->config = App::getContainer()->get(Mobicms\Api\ConfigInterface::class);
    }

    /**
     * Сканирование
     */
    public function scan()
    {
        $this->whiteList = is_file(CACHE_PATH . $this->cacheFile)
            ? include_once CACHE_PATH . $this->cacheFile
            : [];

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

        $configFile = "<?php\n\n" . 'return ' . var_export($filecontents, true) . ";\n";
        file_put_contents(CACHE_PATH . $this->cacheFile, $configFile);
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
                        $file_date = date("d.m.Y H:i:s", filemtime($dir . '/' . $file) + $this->config->timeshift * 3600);

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
