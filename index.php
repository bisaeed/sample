<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 27/04/19
 * Time: 11:54
 */

require_once 'vendor/autoload.php';

$loader = require 'vendor/autoload.php';
$loader->add('Module\\', __DIR__ . '/src/');
$loader->add('Lib\\', __DIR__ . '/src/');

require_once 'routing.php';