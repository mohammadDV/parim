<?php


namespace System\Database\DBConnection;

use PDO;
use PDOException;
use System\Config\Config;
use System\Lib\Exp;

class Connection {
    private static $instance = null;

    private function __construct()
    {

    }

    public static function instance(){

        if (empty(self::$instance)){
            $DBinstance     = new Connection();
            self::$instance = $DBinstance->dbConnection();
        }

        return self::$instance;
    }

    private function dbConnection(){
        $option = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
        try {
            return new PDO("mysql:host=" . Config::get("database.DBHOST") . ";dbname=" . Config::get("database.DBNAME"),Config::get("database.DBUSER"),Config::get("database.DBPASS"),$option);
        }catch (PDOException $e){
            throw new Exp($e->getMessage(), $e->getCode());
        }
    }

    public static function insertID(){
        return self::instance()->lastInsertId();
    }

    public static function beginTransaction(){
        return self::instance()->beginTransaction();
    }

    public static function commit(){
        return self::instance()->commit();
    }

    public static function rollBack(){
        return self::instance()->rollBack();
    }
}