<?php
namespace Controllers;
use \Models\OrderDetailDao;
use \Models\OrderDetailDto;
use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;

class MyPageOrderDetailAction {
    
    private $orderDetailDto;
    private $orderHistoryDto;
        
    public function execute(){

        if(isset($_POST["cmd"]) && $_POST["cmd"] == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        } 
        
        if(!isset($_POST['order_id']) && !isset($_SESSION['order_id'])){
            header('Location:/html/mypage/mypage_order_history.php');
        }
        
        $orderHistoryDao = new OrderHistoryDao();
        $orderDetailDao = new OrderDetailDao();
        
        if(isset($_POST['order_id'])){
            $_SESSION['order_id'] = $_POST['order_id'];
        }
        
        $customerId = $_SESSION['customer_id'];
        $orderId = $_SESSION['order_id'];
        
        $this->orderHistoryDto = $orderHistoryDao->getOrderHistory($customerId, $orderId);
        $this->orderDetailDto = $orderDetailDao->getOrderDetail($orderId);
    }
    

    /** @return OrderHistoryDto */
    public function getOrderHistoryDto(){
        return $this->orderHistoryDto;   
    }   
    
    /** @return OrderDetailDto */
    public function getOrderDetailDto(){
        return $this->orderDetailDto;   
    }   
}

?>
