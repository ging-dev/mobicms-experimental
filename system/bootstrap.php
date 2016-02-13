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
 * Toggle debug mode
 */
defined('DEBUG') || define('DEBUG', false);

/**
 * mobiCMS version
 */
defined('MOBICMS') || define('MOBICMS', true);

/**
 * Directory Separator alias
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Defines the root directory of the mobiCMS installation
 */
define('ROOT_PATH', dirname(__DIR__) . DS);

/**
 * Vendors path
 */
define('VENDOR_PATH', ROOT_PATH . 'system' . DS . 'vendor' . DS);

/**
 * Path to the configuration files
 */
define('CONFIG_PATH', __DIR__ . DS . 'config' . DS);

/**
 * Path to the system cache files
 */
define('CACHE_PATH', __DIR__ . DS . 'cache' . DS);

/**
 * Path to the LOG files
 */
define('LOG_PATH', __DIR__ . DS . 'logs' . DS);

/**
 * Path to the language files
 */
define('LOCALE_PATH', __DIR__ . DS . 'locale' . DS);

/**
 * Path to the modiles
 */
define('MODULE_PATH', ROOT_PATH . 'modules' . DS);

/**
 * Path to the Templates
 */
define('THEMES_PATH', ROOT_PATH . 'themes' . DS);

/**
 * Path to the Upload files
 */
define('FILES_PATH', ROOT_PATH . 'uploads' . DS);

/**
 * Path to the Upload files
 */
define('ASSETS_PATH', ROOT_PATH . 'assets' . DS);


// Profiling
define('START_MEMORY', memory_get_usage());
define('START_TIME', microtime(true));

// Define some PHP settings
mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');
ini_set('session.use_cookies', true);
ini_set('session.use_trans_sid', '0');
ini_set('session.use_only_cookies', true);
ini_set('session.gc_probability', '1');
ini_set('session.gc_divisor', '100');

// Autoloading classes
require __DIR__ . DS . 'vendor/autoload.php';

if (DEBUG) {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 'On');
    ini_set('log_errors', 'On');
    ini_set('error_log', LOG_PATH . 'errors-' . date('Y-m-d') . '.log');
    new Mobicms\Exception\Handler\Handler;
} else {
    ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'Off');
}

// Starting the Firewall
//(new Mobicms\Firewall\Firewall)->run($request->getClientIp());

use Mobicms\Checkpoint\Facade;
use Mobicms\Database\PDOmysql;
use Mobicms\Environment\Vars;  //TODO: доработать, или удалить сервис
use Mobicms\HtmlFilter\Filter; //TODO: доработать, или удалить сервис
use Mobicms\HtmlFilter\Purify; //TODO: доработать, или удалить сервис
use Mobicms\Environment\Network;
use Mobicms\i18n\Translate;
use Mobicms\Routing\Router;
use Mobicms\Template\View;
use Mobicms\Utility\Image;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;

/**
 * Class App
 *
 * @method PDOmysql     db()
 * @method Image        image($file, array $arguments = [], $isModule = false, $imgTag = true)
 * @method Filter       filter($string) //TODO: доработать, или удалить сервис
 * @method              homeurl()
 * @method Translate    lng()
 * @method Network      network()
 * @method Purify       purify($string) //TODO: доработать, или удалить сервис
 * @method              redirect($url) Closure function
 * @method Request      request()
 * @method Response     response()
 * @method Router       router()
 * @method              uri()
 * @method Facade       user()
 * @method Vars         vars() //TODO: удалить
 * @method View         view()
 */
class App extends Mobicms\Ioc\Container
{
}

$app = App::getInstance();

// Initialize the Request
$app->newInstance('request', Request::class);

// Initialize the Response
$app->newInstance('response', Response::class);

// Initialize the Network
$app->newInstance('network', Network::class);

// Initialize the Router
$app->newInstance('router', Router::class);

// Initialize the database connection
$app->newInstance('db', PDOmysql::class,
    [
        'dbHost' => Config\Database::$dbHost,
        'dbName' => Config\Database::$dbName,
        'dbUser' => Config\Database::$dbUser,
        'dbPass' => Config\Database::$dbPass
    ]
);

// Starting the Session and register instance
$session = new Zend\Session\Container('app', App::getDiInstance()->newInstance('Mobicms\Ext\Session\SessionManager'));
$app->setService('session', $session);

// Initialize the User
$app->newInstance('user', Facade::class);

// Initialize the View
$app->newInstance('view', View::class);

// Registering lazy loading services
$app->lazyLoad('image', Image::class, false);
$app->lazyLoad('filter', Filter::class, false); //TODO: доработать, или удалить сервис
$app->lazyLoad('lng', Translate::class);
$app->lazyLoad('purify', Purify::class);        //TODO: доработать, или удалить сервис
$app->lazyLoad('vars', Vars::class);            //TODO: удалить сервис

// Redirect to given URL
$app->setCallable('redirect', function ($url) use ($app) {
    $app->response()->getHeaders()->addHeaderLine('Location', $url[0]);
    $app->response()->setStatusCode(302);
    $app->response()->send();
});

// Get HomeUrl
$app->setCallable('homeurl', function () use ($app) {
    $uri = $app->request()->getUri();

    return $uri->getScheme() . '://' . $uri->getHost() . $app->request()->getBaseUrl();
});

// Get Uri path
$app->setCallable('uri', function () use ($app) {
    return $app->request()->getUri()->getPath();
});

// i18n initialization
$i18n = $app->get('lng');

/**
 * Translate a message
 *
 * @param string $message
 * @param string $domain
 * @return string
 */
function _s($message, $domain = 'default')
{
    global $i18n;

    return $i18n->translateSystem($message, $domain);
}

/**
 * The plural version of _s()
 *
 * @param string $singular
 * @param string $plural
 * @param int    $count
 * @param string $domain
 * @return string
 */
function _sp($singular, $plural, $count, $domain = 'default')
{
    global $i18n;

    return $i18n->translateSystemPlural($singular, $plural, $count, $domain);
}

/**
 * Translate module
 *
 * @param string $message
 * @param string $domain
 * @return string
 */
function _m($message, $domain = 'default')
{
    global $i18n;

    return $i18n->translateModule($message, $domain);
}

/**
 * Plural version of _m()
 *
 * @param string      $singular
 * @param string      $plural
 * @param string      $count
 * @param null|string $domain
 * @return string
 */
function _mp($singular, $plural, $count, $domain = 'default')
{
    global $i18n;

    return $i18n->translateModulePlural($singular, $plural, $count, $domain);
}

// Output buffering
ob_start();

// Shutdown handler
register_shutdown_function(function () use ($app) {
    $app->response()->setContent($app->view()->render());
    $app->response()->send();
    session_register_shutdown(); // This important!
});
