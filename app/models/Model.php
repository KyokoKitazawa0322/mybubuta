<?php
namespace Models;

use \Config\Config;
use \Models\DBConnectionException;

class Model{

    private static $instance = NULL;
    private static $pdo = NULL;
    
    /**
     * @throws DBConnectionException 
     */
    public function __construct(){

        $dbName = getenv('DB_NAME');
        $host = getenv('HOST');
        $dsn = "mysql:dbname=".$dbName.";charset=utf8;host=".$host;        
        $user = getenv('DB_USER');
        $password = getenv('PASSWORD');

        try{
            self::$pdo = new \PDO($dsn, $user, $password, array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_PERSISTENT => true));

        }catch(\PDOException $e){
            throw new DBConnectionException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function getPdo(){
        return self::$pdo;   
    }
    
    private function __wakeup(){
    }

    private function __clone(){
    }
    
    /**
     * @throws DBConnectionException 
     */
    public function beginTransaction(){
        try{
            self::$pdo->beginTransaction();
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * @throws DBConnectionException 
     */
    public function commit(){
        try{
            self::$pdo->commit();
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}