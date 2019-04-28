<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 27/04/19
 * Time: 15:53
 */

namespace Lib\Database;
use \PDO;


class DB extends PDO
{

    private static $dsn = 'mysql:dbname=blog;charset=utf8';
    private static $username = 'saeed';
    private static $password = '1212909w';
    private static $conn;
    private static $query;
    private static $stmt;

    /*
     * create new instance of pdo class
     */
    public function __construct() {

        self::$conn = new PDO(self::$dsn, self::$username, self::$password);
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }

    /*
     * select query
     */
    public function select($columns = '*', $table) {

        self::$query = "SELECT " . $columns . " FROM " . $table;
        return $this;

    }

    /*
     * bind params with placeholder
     */
    public function params($placeholder, $column) {

        self::$stmt->bindParam($placeholder, $column);
        return $this;
    }

    /*
     * get raw sql
     */
    public function getQuery() {

        self::$stmt = self::$conn->prepare(self::$query);
        return $this;
    }

    /*
     * execute query
     */
    public function execution($method = null) {

        self::$stmt->execute();
        if(!is_null($method))
            self::$stmt->setFetchMode($method);
        self::$query = '';
        return $this;

    }

    /*
     * where condition for query
     */
    public function where($condition) {

        self::$query .= ' WHERE ' . $condition;
        return $this;

    }

    /*
     * fetch result from executed query
     */
    public function fAll() {

        return self::$stmt->fetchAll();
    }

    /*
     * get db instance
     */
    public function getDB() {

        return self::$conn;
    }

    /*
     * insert query
     */
    public function insert($columns, $table, $placeholders) {

        self::$query = "INSERT INTO " . $table . "(" . $columns . ") VALUES (" . $placeholders . ")" ;
        return $this;

    }

    /*
     * get last inserted row id
     */
    public function getInsertedId() {

        return self::$conn->lastInsertId();
    }
}