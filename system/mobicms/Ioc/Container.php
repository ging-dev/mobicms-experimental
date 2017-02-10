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

namespace Mobicms\Ioc;

use Interop\Container\ContainerInterface;
use Mobicms\Ioc\Exception\NotFoundException;
use Zend\Config\Writer\PhpArray as ConfigWriter;
use Zend\Di\Di;
use Zend\Di\DefinitionList;
use Zend\Di\Definition\ArrayDefinition;
use Zend\Di\Definition\RuntimeDefinition;
use Zend\Di\Definition\CompilerDefinition;

/**
 * Class Container
 *
 * @package Mobicms\IoC
 * @author  Oleg (AlkatraZ) Kasyanov <dev@mobicms.net>
 * @version v.2.0.0 2015-12-09
 *
 * @method \Zend\Session\Container  session()
 */
class Container implements ContainerInterface
{
    /**
     * @var Di
     */
    protected static $di;
    protected static $instance;

    private $services = [];
    private $callable = [];
    private $map = [];

    private function __construct()
    {
        static::$di = new Di(new DefinitionList(
            [
                new ArrayDefinition($this->getDefinition()),
                new RuntimeDefinition(),
            ]
        ));
    }

    public function __call($name, $arguments)
    {
        return $this->get($name, $arguments);
    }

    /**
     * Get Container instance
     *
     * @return $this
     */
    public static function getInstance()
    {
        return null === static::$instance ? static::$instance = new static : static::$instance;
    }

    /**
     * Get Dependency Injector instance
     *
     * @return Di
     */
    public static function getDiInstance()
    {
        if (null === static::$di) {
            static::getInstance();
        }

        return static::$di;
    }

    /**
     * Finds an entry of the container by its identifier and returns it
     *
     * @param string $name
     * @throws NotFoundException  No entry was found for this identifier
     * @return mixed
     */
    public function get($name)
    {
        $args = func_get_args();
        $arguments = isset($args[1]) ? $args[1] : [];

        if (isset($this->services[$name])) {
            return $this->services[$name];
        } elseif (isset($this->map[$name])) {
            return $this->map[$name]['shared']
                ? $this->services[$name] = static::$di->get($this->map[$name]['class'], ['arguments' => $arguments])
                : static::$di->newInstance($this->map[$name]['class'], ['arguments' => $arguments], false);
        } elseif (isset($this->callable[$name])) {
            return $this->callable[$name]($arguments);
        } else {
            throw new NotFoundException('Method ' . $name . '() not found');
        }
    }

    /**
     * Check whether a service member?
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->services[$name]) || isset($this->map[$name]) || isset($this->callable[$name]);
    }

    /**
     * Register the instance of service
     *
     * @param string $name
     * @param mixed  $service
     */
    public function setService($name, $service)
    {
        try {
            $this->checkExists($name);
            $this->services[$name] = $service;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }

    /**
     * Initialize and register the class via Dependency Injection
     *
     * @param string $alias
     * @param string $class
     * @param array  $parameters
     */
    public function newInstance($alias, $class, array $parameters = [])
    {
        try {
            $this->checkExists($alias);

            if (!empty($parameters)) {
                static::$di->instanceManager()->setParameters($class, $parameters);
            }

            $this->services[$alias] = static::$di->newInstance($class);
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }

    /**
     * Register the callable function
     *
     * @param $name
     * @param $callable
     */
    public function setCallable($name, $callable)
    {
        try {
            $this->checkExists($name);
            $this->checkCallable($callable);
            $this->callable[$name] = $callable;
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }

    /**
     * Register class for lazy loading
     *
     * @param      $name
     * @param      $service
     * @param bool $shared
     */
    public function lazyLoad($name, $service, $shared = true)
    {
        try {
            $this->checkExists($name);
            $this->map[$name] = ['class' => $service, 'shared' => $shared];
        } catch (\InvalidArgumentException $e) {
            throw $e;
        }
    }

    /**
     * Compile Di definitions
     *
     * @return array
     */
    private function getDefinition()
    {
        $file = CACHE_PATH . 'ioc.cache';

        if (!is_file($file)) {
            $diCompiler = new CompilerDefinition;
            $diCompiler->addDirectory(ROOT_PATH . 'system/mobicms');
            $diCompiler->compile();
            $definition = $diCompiler->toArrayDefinition()->toArray();
            $configFile = "<?php\n\n" . 'return ' . var_export($definition, true) . ";\n";
            file_put_contents($file, $configFile);

            return $definition;
        } else {
            return include $file;
        }
    }

    private function checkExists($name)
    {
        if ($this->has($name)) {
            throw new \InvalidArgumentException('The service [' . $name . '] already exists');
        }
    }

    private function checkCallable($callable)
    {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException('The argument [' . $callable . '] it is not callable');
        }
    }
}
