<?php

/*
 * --------------------------------
 *           Let's begin
 * --------------------------------
 *
 */
header('Content-Type: text/html; charset=utf-8');
@set_include_path(dirname(__FILE__));
date_default_timezone_set('Europe/Paris');

$root = substr(dirname($_SERVER['PHP_SELF']), 1) == '' ? '' : substr(dirname($_SERVER['PHP_SELF']), 1) . '/';
define('ROOT', (strpos($root, 'index.php/') !== false ? substr($root, 0, strpos($root, 'index.php/')) : $root));
define('HTTP_ROOT', '/' . ROOT);
define('PHP_ROOT', rtrim(dirname(__FILE__), '/') . '/');
define('IN_ENV', true);

define('APP', PHP_ROOT . 'app/');
define('CORE', APP . 'core/');
define('CONTROLLER', APP . 'controller/');
define('MODEL', APP . 'model/');
define('VIEW', APP . 'view/');
define('FILES', 'files/');


/*
 * --------------------------------
 *            Set time
 * --------------------------------
 *
 */
$dateTimeZone = new DateTimeZone('Europe/Paris');
$dateTime = new DateTime("now", $dateTimeZone);
$timeOffset = $dateTimeZone->getOffset($dateTime);
define('TIME', time() + $timeOffset);
// Manually change time
// define('TIME', strtotime('21-03-2016 10:42'));


/*
 * --------------------------------
 *          Init Models
 * --------------------------------
 *
 */
require_once (CORE . 'Model.class.php');
foreach (glob(CORE . '*.php') as $filename)
    require_once $filename;
foreach (glob(MODEL . '*.class.php') as $filename)
    require_once $filename;


/*
 * --------------------------------
 *         Analyse request
 * --------------------------------
 *
 */
if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
    define('POST', true);
    define('GET', false);
} else {
    define('POST', false);
    define('GET', true);
}


/*
 * --------------------------------
 *             Router
 * --------------------------------
 *
 */
$router = new Router;
$router->parse($_SERVER['REQUEST_URI']);

$core = new Core;
$core->start();
$core->follow($router);


/*
 * --------------------------------
 *       Helpers functions
 * --------------------------------
 *
 */
function e($html) {
    return htmlentities($html, ENT_QUOTES, 'UTF-8');
}
