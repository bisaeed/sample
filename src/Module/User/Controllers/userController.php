<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 27/04/19
 * Time: 12:11
 */

namespace Module\Controllers;

use \Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Lib\Database\DB;
use Lib\Validation\validation;
use Lib\Auth\Auth;

class userController
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

    /*
     * user register
     */
    public function register(Request $request) {

        // get user information
        $user = (object) $request->request->all();

        // get real ip from request
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // -------------- validation --------------
        $validate = validation::run($user,[
            'email' => 'notEmpty',
            'mobile' => 'notEmpty',
            'password' => 'notEmpty',
            'password' => ['min','6'],
            'confirm_password' => ['confirm_password',$user->password],
        ]);

        if(!empty($validate)) {

            echo json_encode([
                'status ' => 'validation error',
                'message' => $validate
            ]);die;
        }
        // --------------- validation ----------------

        // check one user with this email or mobile exist or not
        $result = $this->db->select('name, email','users')
            ->where('email = :email OR mobile = :mobile')
            ->getQuery()
            ->params(':email', $user->email)
            ->params(':mobile', $user->mobile)
            ->execution(\PDO::FETCH_ASSOC)
            ->fAll();

        if(empty($result)) {

            $this->db->insert('name, email, mobile, password, ip','users', ':name, :email, :mobile, :password, :ip')
            ->getQuery()
            ->params(':name', $user->name)
            ->params(':email', $user->email)
            ->params(':mobile', $user->mobile)
            ->params(':password', md5($user->password))
            ->params(':ip', $ip)
            ->execution();

            $response = [
                'status' => true,
                'message' => 'user registered successfully'
            ];
        }
        else {
            error_log('user registered before - ' . date('Y-m-d H:i:s'), 3, '/var/www/blog/log.txt');
            $response = [
                'status' => false,
                'message' => 'user registered before'
            ];
        }

        echo json_encode($response);die;

    }

    /*
     * user login
     */
    public function login(Request $request) {

        // get user information
        $user = (object) $request->request->all();

        // ------------------- validation ------------------
        $validate = validation::run($user,[
            'email' => 'notEmpty',
            'password' => 'notEmpty',
        ]);

        if(!empty($validate)) {

            echo json_encode([
                'status ' => 'validation error',
                'message' => $validate
            ]);die;
        }
        // -------------------- validation -----------------

        // check user exist or not
        $result = $this->db->select('*','users')
            ->where('email = :email AND password = :password')
            ->getQuery()
            ->params(':email', $user->email)
            ->params(':password', md5($user->password))
            ->execution(\PDO::FETCH_ASSOC)
            ->fAll();

        if(empty($result)) {

            error_log('email or password incorrect.try again Please - ' . date('Y-m-d H:i:s'), 3, '/var/www/blog/log.txt');
            $response = [
                'status' => false,
                'message' => 'email or password incorrect.try again Please'
            ];
        }
        else {

            $object = (object) $result[0];

            $response = [
                'status' => true,
                'message' => 'login successfully',
                'token' => $this->createToken($object)
            ];
        }

        echo json_encode($response);die;

    }

    /*
     * create token for test
     */
    public function createToken($data) {

        $issuedAt   = time();
        $notBefore  = $issuedAt + 10;             //Adding 10 seconds
        $expire     = $notBefore + 600;            // Adding 60 seconds
        $serverName = 'http://localhost:8080';    // Retrieve the server name from config file

        $token = array(
            "iss" => $serverName,
            "aud" => $serverName,
            'iat'  => $issuedAt,         // Issued at: time when the token was generated
//            'nbf'  => $notBefore,      // Not before
            'exp'  => $expire,           // Expire
            'data' => [                  // Data related to the signer user
                'name' => $data->name,
                'email' => $data->email,
                'mobile' => $data->mobile,
            ]
        );

        $jwt = JWT::encode($token, $this->key, 'HS256');

        return $jwt;

    }

    public function create(Request $request) {

        $token = $request->headers->get('auth-token');

        if(Auth::isLogin($token, $this->key)) {


        }
        else {

        }

    }
}