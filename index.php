<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 27/04/19
 * Time: 11:54
 */

require_once 'vendor/autoload.php';

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

$loader = require 'vendor/autoload.php';
$loader->add('Module\\', __DIR__ . '/src/');
$loader->add('Lib\\', __DIR__ . '/src/');

$db = new Lib\Database\DB();

require_once 'config.php';
require_once 'routing.php';