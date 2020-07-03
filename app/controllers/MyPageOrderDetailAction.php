<?php
namespace Controllers;

use \Models\OrderDetailDao;
use \Models\OrderDetailDto;
use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;


class MyPageOrderDetailAction {
    
    private $orderDetailDto;
    private $orderHistoryDto;
        
    public function execute(){

        $cmd = filter_input(INPUT_POST, 'cmd');
        $orderId = filter_input(INPUT_POST, 'order_id');
        
        if($orderId){
            $_SESSION['order_id'] = $orderId;
        }
        
        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
        
        
        if(isset($_SESSION['order_id'])){
            $orderId = $_SESSION['order_id'];   
        }
        try{
            if(!$orderId){
                throw new InvalidParamException('Invalid param for order-detail:$orderId="nothing"');
            }
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }
        
        $orderHistoryDao = new OrderHistoryDao();
        $orderDetailDao = new OrderDetailDao();
       
        try{
            $this->orderHistoryDto = $orderHistoryDao->getOrderHistory($customerId, $orderId);
            $this->orderDetailDto = $orderDetailDao->getOrderDetail($orderId);
        
        } catch(MyPDOException $e){
            $e->handler($e);

        }catch(DBParamException $e){
            $e->handler($e);
        }
    }
    
    public function getOrderHistoryDto(){
        return $this->orderHistoryDto;   
    }   

    public function getOrderDetailDto(){
        return $this->orderDetailDto;   
    }   
}

?>
