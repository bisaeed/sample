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


if('/register' === $uri && $method === 'POST') {

    $action = new Module\Controllers\userController($db,$publicKey);
    $action->register($request);
}

if('/login' === $uri && $method === 'POST') {

    $action = new Module\Controllers\userController($db,$publicKey);
    $action->login($request);
}

if('/token/create' === $uri && $method === 'GET') {

    $action = new Module\Controllers\userController($db,'',$privateKey);
    $action->createToken();
}