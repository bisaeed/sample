<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 27/04/19
 * Time: 15:53
 */

namespace Lib\Database;

class DB
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "1212909w";

    public function getInstance() {

//        $conn = new \mysqli($this->servername, $this->username, $this->password);
//
//// Check connection
//        if ($conn->connect_error) {
//            die("Connection failed: " . $conn->connect_error);
//        }
//        echo "Connected successfully";
//echo 'test';die;
        try
        {
            $conn = new \PDO("mysql:host=$this->servername;dbname=blog", $this->username, $this->password);
            print_r($conn);die;
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";die;
        }
        catch(PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();die;
        }
    }
}