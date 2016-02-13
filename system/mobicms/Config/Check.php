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
 * Class Check
 *
 * @package Mobicms\Config
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.1.0.0 2015-08-09
 */
class Check
{
    public function checkPath($path)
    {
        if (!is_dir($path)) {
            throw new \InvalidArgumentException('The directory [' . $path . '] does not exist');
        }
    }

    /**
     * Check Namespace array
     *
     * @param array $namespace
     */
    public function checkNamespaceArray(array $namespace)
    {
        foreach ($namespace as $val) {
            try {
                $this->checkName($val);
            } catch (\InvalidArgumentException $e) {
                throw $e;
            }
        }
    }

    /**
     * Check data array
     *
     * @param array $data
     */
    public function checkData(array $data)
    {
        foreach ($data as $key => $value) {
            try {
                $this->checkName($key);

                if (is_array($value)) {
                    $this->checkArray($value);
                } else {
                    $this->checkType($value);
                }
            } catch (\InvalidArgumentException $e) {
                throw $e;
            }
        }
    }

    /**
     * Check the key
     *
     * @param string $key
     */
    public function checkName($key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('The name can\'t be empty');
        } elseif (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*/', $key)) {
            throw new \InvalidArgumentException($key . ' is invalid name');
        } elseif ($key == 'this') {
            throw new \InvalidArgumentException('$this is a special variable that can\'t be assigned');
        }
    }

    /**
     * Check type of the value
     *
     * @param mixed $value
     */
    private function checkType($value)
    {
        if (is_resource($value) || is_object($value)) {
            throw new \InvalidArgumentException('Resources or Objects can\'t be used as values');
        }
    }

    /**
     * Check array values
     *
     * @param array $array
     * @return array
     */
    private function checkArray(array $array)
    {
        foreach ($array as $key => $val) {
            if (is_array($array[$key])) {
                $this->checkArray($array[$key]);
            } else {
                $this->checkType($array[$key]);
            }
        }

        return $array;
    }
}
