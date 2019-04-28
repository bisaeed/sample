<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 28/04/19
 * Time: 13:24
 */

namespace Lib\Validation;

class rules
{

    public static $message = [];

    public static function notEmpty($name = null,$value) {

        if(empty($value)) {

            self::$message[] = $name . ' Should not be empty.';
            return true;
        }

        return false;
    }

    public static function min($name = null, $value, $min = 6) {

        if(strlen($value) < $min) {

            self::$message[] = $name . ' length should not be min than ' . $min . ' ';
            return true;
        }

        return false;
    }

    public static function confirm_password($name = null, $confirm_password, $password) {

        if($password != $confirm_password) {

            self::$message[] = 'password not equals with confirm password';
            return true;
        }

        return false;
    }
    
}