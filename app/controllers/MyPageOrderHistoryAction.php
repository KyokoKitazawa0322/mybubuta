<?php
namespace Controllers;
use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;

class MyPageOrderHistoryAction {

    private $orders = [];
    
    public function execute(){
        
        if(isset($_POST["cmd"]) && $_POST["cmd"] == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        } 

        $orderHistoryDao = new OrderHistoryDao();
        $customerId = $_SESSION['customer_id'];
        
        $this->orders = $orderHistoryDao->getAllOrderHistory($customerId);
    }
    
    /** @return OrderHistoryDto */
    public function getOrders(){
        return $this->orders;   
    }   
}

?>
