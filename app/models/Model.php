<?php
namespace Models;

use \Config\Config;

class Model{

    protected $pdo = NULL;

    public function __construct(){
        
        $dbName = getenv('DB_NAME');
        $host = getenv('HOST');
        $dsn = "mysql:dbname=".$dbName.";charset=utf8;host=".$host;        
        $user = getenv('DB_USER');
        $password = getenv('PASSWORD');

        if((!$this->pdo instanceof PDO)){
            try{
                $this->pdo = new \PDO($dsn, $user, $password, array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_PERSISTENT => true));
                
            }catch(\PDOException $e){
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                Config::outputLog($e->getCode(), '接続エラー:'.$e->getMessage(), $e->getTraceAsString());
                exit('接続エラーが発生しました。しばらくたってから再度アクセスしてください。');
            }
        }
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}