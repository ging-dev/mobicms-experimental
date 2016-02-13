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

namespace Mobicms\Config;

/**
 * Class WriteHandler
 *
 * @package Mobicms\Config
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-08-09
 */
class WriteHandler
{
    /**
     * @var Check
     */
    private $check;

    private $namespace = [];

    private $path;

    /**
     * @param string $rootNamespace
     * @param string $path
     */
    public function __construct($rootNamespace = null, $path = null)
    {
        $this->check = new Check;

        if (null === $rootNamespace || ucfirst($rootNamespace) == 'Config') {
            $this->path = CONFIG_PATH;
            $this->namespace[] = 'Config';
        } else {
            try {
                $this->check->checkName($rootNamespace);
                $this->check->checkPath($path);
                $this->namespace[] = $rootNamespace;
                $this->path = $path;
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException($e->getMessage());
            }
        }
    }

    /**
     * Add Namespace
     *
     * @param string $namespace
     * @return $this
     */
    public function addNamespace($namespace)
    {
        try {
            $namespace = explode('\\', trim($namespace, '\\'));
            $this->check->checkNamespaceArray($namespace);
            $this->namespace = array_merge($this->namespace, $namespace);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        return $this;
    }

    /**
     * Write config
     *
     * @param string $class
     * @param array  $data
     * @param bool   $checkProperties
     */
    public function write($class, array $data, $checkProperties = true)
    {
        try {
            $this->check->checkName($class);
            $this->check->checkData($data);
            $dir = $this->prepareDir();
            $this->writeFile($dir, $class, $data, $checkProperties);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Prepare directory
     *
     * @return string
     */
    private function prepareDir()
    {
        $dir = trim($this->path, '/\\') . DS;

        foreach (array_slice($this->namespace, 1) as $val) {
            $dir .= $val . DS;

            if (is_dir($dir)) {
                continue;
            }

            if (!mkdir($dir)) {
                throw new \InvalidArgumentException('Unable to create directory: ' . $dir);
            }
        }

        return $dir;
    }

    /**
     * Prepare config data
     *
     * @param array $data
     * @return string
     */
    private function prepareData(array $data, $class, $check)
    {
        $out = [];

        if ($check && ($properties = get_class_vars($class)) !== false) {
            $this->prepareProperties($out, $properties, $data);
        } else {
            $this->prepareProperties($out, $data, $data);
        }

        return implode("\n", $out);
    }

    /**
     * Prepare the Properties
     *
     * @param array $out
     * @param array $checkData
     * @param array $data
     */
    private function prepareProperties(array &$out, array $checkData, array $data)
    {
        foreach ($checkData as $key => $val) {
            $out[] = '    public static $' . $key . ' = ' . var_export((isset($data[$key]) ? $data[$key] : $val), true) . ';';
        }
    }

    /**
     * Creating a class and write it to a file
     *
     * @param string $dir
     * @param string $class
     * @param array  $data
     * @param bool   $check
     */
    private function writeFile($dir, $class, array $data, $check)
    {
        $namespace = implode('\\', $this->namespace);
        $code = [
            '<?php' . "\n",
            'namespace ' . $namespace . ';' . "\n",
            'class ' . $class . "\n{",
            $this->prepareData($data, $namespace . '\\' . $class, $check),
            "}\n",
        ];

        if (!file_put_contents($dir . $class . '.php', implode("\n", $code))) {
            throw new \RuntimeException('Unable to write config: ' . $class);
        }

        clearstatcache(true, $dir . $class . '.php');

        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($dir . $class . '.php');
        }
    }
}
