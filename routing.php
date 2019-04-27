<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 27/04/19
 * Time: 12:36
 */

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

$uri = $request->getPathInfo();
$method = $request->getMethod();


if('/register' === $uri && $method === 'GET') {

    $action = new Module\Controllers\userController();
    $action->register($request);
}

if('/token/create' === $uri && $method === 'GET') {

    $action = new Module\Controllers\userController();
    $action->createToken();
}