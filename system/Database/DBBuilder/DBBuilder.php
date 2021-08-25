<?php

namespace System\Database\DBBuilder;

use System\Config\Config;
use System\Database\DBConnection\Connection;

class DBBuilder {
    public function __construct($delete = false)
    {
        if ($delete){
            $this->removeTables();
            die("Your database has been successfully reset");
        }

        $this->createTables();
        die("migrations run successfully");
    }

    private function createTables(){
        $migrations = $this->getMigrations();
        $connect    = Connection::instance();
        foreach ($migrations as $migration) {
            if (!empty($migration)){
                $statement = $connect->prepare($migration);
                $statement->execute();
            }
        }
        return true;
    }

    private function removeTables(){
        $filename   = Config::get("app.BASE_DIR") . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "migrations" . DIRECTORY_SEPARATOR . "remove.php";
        $sqlCode    = require $filename;
        $migration  = $sqlCode[0];
        if (!empty($migration)){
            $connect    = Connection::instance();
            $statement = $connect->prepare($migration);
            $statement->execute();
        }
        return true;
    }

    private function getMigrations(){
        $oldArray   = $this->getOldMigration();
        $directory  = Config::get("app.BASE_DIR") . DIRECTORY_SEPARATOR . "database" . DIRECTORY_SEPARATOR . "migrations" . DIRECTORY_SEPARATOR;
        $allArray   = glob($directory.'*.php');
        $newArray   = array_diff($allArray,$oldArray);

        $this->putOldMigration($allArray);

        $sqlArray   = [];
        foreach ($newArray as $filename) {
            $sqlCode = require $filename;
            $sqlArray[] = $sqlCode[0];
        }
        return $sqlArray;
    }

    private function getOldMigration()
    {
        $data = file_get_contents(__DIR__ . "/oldTables.db");
        return empty($data) ? [] : unserialize($data);
    }

    private function putOldMigration($value)
    {
        file_put_contents(__DIR__ . "/oldTables.db",serialize($value));
    }
}