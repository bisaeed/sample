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


class userController
{

    public $db;
    public $publicKey;
    public $privateKey;

    public function __construct(DB $db,$publicKey = null,$privateKey = null)
    {
        $this->db = $db;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    /*
     * user register
     */
    public function register(Request $request) {

        // get token from request header
        $token = $request->headers->get('auth-token');

        // decode token
        $decodedArray = (array) JWT::decode($token, $this->publicKey, array('RS256'));

        // get real ip from request
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // -------------- validation --------------
        $validate = validation::run($decodedArray['data'],[
            'email' => 'notEmpty',
            'mobile' => 'notEmpty',
            'password' => 'notEmpty',
            'password' => ['min','6'],
            'confirm_password' => ['confirm_password',$decodedArray['data']->password],
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
            ->params(':email', $decodedArray['data']->email)
            ->params(':mobile', $decodedArray['data']->mobile)
            ->execution(\PDO::FETCH_ASSOC)
            ->fAll();

        if(empty($result)) {

            $this->db->insert('name, email, mobile, password, ip','users', ':name, :email, :mobile, :password, :ip')
            ->getQuery()
            ->params(':name', $decodedArray['data']->name)
            ->params(':email', $decodedArray['data']->email)
            ->params(':mobile', $decodedArray['data']->mobile)
            ->params(':password', md5($decodedArray['data']->password))
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

        // get token from request header
        $token = $request->headers->get('auth-token');

        // decode token
        $decodedArray = (array) JWT::decode($token, $this->publicKey, array('RS256'));

        // ------------------- validation ------------------
        $validate = validation::run($decodedArray['data'],[
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
            ->params(':email', $decodedArray['data']->email)
            ->params(':password', md5($decodedArray['data']->password))
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

            $response = [
                'status' => true,
                'message' => 'login successfully'
            ];
        }

        echo json_encode($response);die;

    }

    /*
     * create token for test
     */
    public function createToken() {

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
//                'name' => 'reza',
                'email' => 'reza@gmail.com',
//                'mobile' => '09339635143',
                'password' => '12345',
//                'confirm_password' => '123456',
            ]
        );

        $jwt = JWT::encode($token, $this->privateKey, 'RS256');
        echo "Encode:\n" . print_r($jwt, true) . "\n";
    }
}