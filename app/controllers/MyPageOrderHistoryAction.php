<?php
namespace Controllers;
use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\OriginalException;
use \Config\Config;

class MyPageOrderHistoryAction {

    private $orders = [];
    
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }

        $orderHistoryDao = new OrderHistoryDao();
        
        try{
            $this->orders = $orderHistoryDao->getAllOrderHistory($customerId);
       
        } catch(\PDOException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            die('エラー:データベースの処理に失敗しました。');
        }
    }
    
    public function getOrders(){
        return $this->orders;   
    }   
}

?>
