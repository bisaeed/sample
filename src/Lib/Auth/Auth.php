<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 29/04/19
 * Time: 09:56
 */

namespace Lib\Auth;

use Lib\Database\DB;
use \Firebase\JWT\JWT;

class Auth
{

    private static $user;

    public static function isLogin($token, $key) {

        try {

            // decode token
            $user = (array) JWT::decode($token, $key, array('HS256'));

            $db = DB::getDB();

            self::$user = $db->select(' * , COUNT(id) AS count','users')
                ->where('email = :email AND mobile = :mobile')
                ->getQuery()
                ->params(':email', $user['data']->email)
                ->params(':mobile', $user['data']->mobile)
                ->execution(\PDO::FETCH_ASSOC)
                ->fOne();

            return self::$user['count'];
        }
        catch (Exception $e) {

            print_r('error : ' . $e->getMessage());die;

        }
    }

    public static function userId() {

        return self::$user['id'];
    }
}