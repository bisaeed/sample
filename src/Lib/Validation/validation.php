<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 28/04/19
 * Time: 13:54
 */

namespace Lib\Validation;
use Lib\Validation\rules;

class validation
{

    // apply rules validation on data
    public static function run($data,$rules = array()) {

        foreach ($rules as $key => $value) {

            if(is_array($value)) {
                call_user_func_array('Lib\Validation\rules::'.$value[0],[$key,$data->$key,$value[1]]);
            }
            else {
                call_user_func_array('Lib\Validation\rules::'.$value,[$key,$data->$key]);
            }
        }

        return rules::$message;
    }

}