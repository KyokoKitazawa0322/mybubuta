<?php
namespace Controllers;

use \Models\OrderDetailDao;
use \Models\OrderDetailDto;
use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class MyPageOrderDetailAction {
    
    private $orderDetailDto;
    private $orderHistoryDto;
        
    public function execute(){

        $cmd = Config::getPOST("cmd");
        $orderId = Config::getGet("order_id");
        
        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
    
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }

        try{
            if(!$orderId){
                throw new InvalidParamException('Invalid param for order-detail:$orderId="nothing"');
            }
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }
        
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $orderHistoryDao = new OrderHistoryDao($pdo);
            $orderDetailDao = new OrderDetailDao($pdo);
            $this->orderHistoryDto = $orderHistoryDao->getOrderHistory($customerId, $orderId);
            $this->orderDetailDto = $orderDetailDao->getOrderDetail($orderId);
        
        }catch(DBConnectionException $e){
            $e->handler($e);   
            
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
