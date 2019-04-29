<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 29/04/19
 * Time: 10:59
 */

namespace Module\Post\Controllers;

use \Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Lib\Database\DB;
use Lib\Validation\validation;
use Lib\Auth\Auth;

class postController
{

    public $db;
    public $key;
    public $secretKey;

    public function __construct(DB $db,$key = null, $secretKey = null)
    {
        $this->db = $db;
        $this->key = $key;
        $this->secretKey = $secretKey;
    }

    public function create(Request $request)
    {

        $token = $request->headers->get('auth-token');

        if (Auth::isLogin($token, $this->key)) {


        } else {

        }
    }

}