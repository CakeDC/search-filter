<?php
declare(strict_types=1);

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\Fixture\SchemaLoader;

/**
 * Test suite bootstrap
 *
 * This function is used to find the location of CakePHP whether CakePHP
 * has been installed as a dependency of the plugin, or the plugin is itself
 * installed as a dependency of an application.
 */
$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = dirname($root);
        if (is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);

    throw new Exception('Cannot find the root of the application, unable to run tests');
};
$root = $findRoot(__FILE__);
unset($findRoot);

chdir($root);

require $root . '/vendor/cakephp/cakephp/src/functions.php';
require_once $root . '/vendor/autoload.php';

define('ROOT', $root . DS . 'tests' . DS . 'test_app' . DS);
define('APP', ROOT . 'App' . DS);
define('CONFIG', ROOT . 'config' . DS);
define('WWW_ROOT', ROOT . 'webroot' . DS);
define('TESTS', $root . DS . 'tests' . DS);
define('CAKE_CORE_INCLUDE_PATH', $root . '/vendor/cakephp/cakephp');
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);
define('TMP', $root . DS . 'tmp' . DS);
define('LOGS', TMP . 'logs' . DS);
define('CACHE', TMP . 'cache' . DS);

Configure::write('debug', true);

Configure::write('App', [
    'namespace' => 'CakeDC\SearchFilter\Test\App',
    'encoding' => 'UTF-8',
    'debug' => true,
    'paths' => [
        'templates' => [ROOT . 'templates' . DS],
    ],
]);

Configure::write('App.encoding', 'utf8');
Configure::write('debug', true);

@mkdir(TMP . 'cache/models', 0777);
@mkdir(TMP . 'cache/persistent', 0777);
@mkdir(TMP . 'cache/views', 0777);

$cache = [
    'default' => [
        'engine' => 'File',
    ],
    '_cake_core_' => [
        'className' => 'File',
        'prefix' => 'search_myapp_cake_core_',
        'path' => CACHE . 'persistent/',
        'serialize' => true,
        'duration' => '+10 seconds',
    ],
    '_cake_model_' => [
        'className' => 'File',
        'prefix' => 'search_my_app_cake_model_',
        'path' => CACHE . 'models/',
        'serialize' => 'File',
        'duration' => '+10 seconds',
    ],
];

Cache::setConfig($cache);
Configure::write('Session', [
    'defaults' => 'php',
]);

if (!getenv('db_dsn')) {
    putenv('db_dsn=sqlite:///:memory:');
}

ConnectionManager::setConfig('test', [
    'url' => getenv('db_dsn'),
    'timezone' => 'UTC',
]);

if (env('FIXTURE_SCHEMA_METADATA')) {
    $loader = new SchemaLoader();
    $loader->loadInternalFile(env('FIXTURE_SCHEMA_METADATA'));
}

Configure::write('Plugin.CakeDC/SearchFilter', [
    'path' => dirname(dirname(__FILE__)) . DS,
]);
