<?php
namespace Controllers;

use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

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
        
        unset($_SESSION['order_id']);
        
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $orderHistoryDao = new OrderHistoryDao($pdo);
            $this->orders = $orderHistoryDao->getAllOrderHistory($customerId);
        
        }catch(DBConnectionException $e){
            $e->handler($e);   
       
        } catch(MyPDOException $e){
            $e->handler($e);
        }
    }
    
    public function getOrders(){
        return $this->orders;   
    }   
}

?>
