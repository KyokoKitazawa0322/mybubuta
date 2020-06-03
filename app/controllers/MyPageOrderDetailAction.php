<?php
namespace Controllers;
use \Models\OrderDetailDao;
use \Models\OrderDetailDto;
use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\OriginalException;
use \Config\Config;

class MyPageOrderDetailAction {
    
    private $orderDetailDto;
    private $orderHistoryDto;
        
    public function execute(){

        $cmd = filter_input(INPUT_POST, 'cmd');
        $orderId = filter_input(INPUT_POST, 'order_id');
        if(isset($_SESSION['order_id'])){
            $orderId = $_SESSION['order_id'];   
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
        
        if(!$orderId){
            header('Location:/html/mypage/mypage_order_history.php');
        }
        
        $orderHistoryDao = new OrderHistoryDao();
        $orderDetailDao = new OrderDetailDao();
       
        try{
            $this->orderHistoryDto = $orderHistoryDao->getOrderHistory($customerId, $orderId);
            $this->orderDetailDto = $orderDetailDao->getOrderDetail($orderId);
        
        } catch(\PDOException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            die('エラー:データベースの処理に失敗しました。');

        }catch(OriginalException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
            header('Content-Type: text/plain; charset=UTF-8', true, 400);
            die('エラー:'.$e->getMessage());
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
