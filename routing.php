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

    $action = new Module\User\Controllers\userController($db);
    $action->register($request);
}

if('/login' === $uri && $method === 'POST') {

    $action = new Module\User\Controllers\userController($db,$key);
    $action->login($request);
}

if('/post/create' === $uri && $method === 'POST') {

    $action = new Module\Post\Controllers\postController($db,$key);
    $action->create($request);
}

if('/post/edit' === $uri && $request->query->has('id') && $method === 'POST') {

    $action = new Module\Post\Controllers\postController($db,$key);
    $action->edit($request, $request->query->get('id'));
}

if('/post/delete' === $uri && $request->query->has('id') && $method === 'POST') {

    $action = new Module\Post\Controllers\postController($db,$key);
    $action->delete($request, $request->query->get('id'));
}

if('/post/comment/add' === $uri && $request->query->has('id') && $method === 'POST') {

    $action = new Module\Post\Controllers\postController($db,$key);
    $action->addComment($request, $request->query->get('id'));
}