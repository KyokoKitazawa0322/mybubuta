<?php
namespace Controllers;
use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
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
        
        unset($_SESSION['order_id']);
        $orderHistoryDao = new OrderHistoryDao();
        
        try{
            $this->orders = $orderHistoryDao->getAllOrderHistory($customerId);
       
        } catch(MyPDOException $e){
            $e->handler($e);
        }
    }
    
    public function getOrders(){
        return $this->orders;   
    }   
}

?>
