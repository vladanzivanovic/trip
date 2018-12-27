<?php
require_once realpath(__DIR__."/vendor/autoload.php");

$httpProtocol = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') ? 'http' : 'https';
define("BASE_URL", $httpProtocol."://{$_SERVER['HTTP_HOST']}");
define("BASE_PATH", dirname(__FILE__));
define("VIEWS_PATH", BASE_PATH.'/src/Resources/Views');
define("PAGES_PATH", VIEWS_PATH.'/Pages');

use \CoreBundle\Extensions\ParameterExtension;
use \CoreBundle\Lib\TestRouter;

$parameters = new ParameterExtension();

$router = $parameters->getRouter();
$router->match();

$displayErrors = 0;

if($parameters->getParameter('environment') === 'dev') {
    $displayErrors = 1;
}

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', $displayErrors);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

