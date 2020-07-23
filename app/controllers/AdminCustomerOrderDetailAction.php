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
use \Models\MyPDOException;
use \Models\DBConnectionException;

class AdminCustomerOrderDetailAction{
    
    private $orderDetailDto;
    private $orderHistoryDto;
    private $customerId;
        
    public function execute(){
        
        /*====================================================================
      　  $_SESSION['admin_id']がなければadmin_login.phpへリダイレクト
        =====================================================================*/
        $cmd = Config::getPOST("cmd");
        
        if($cmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }
        
        $customerId = Config::getGET("customer_id");
        $orderId = Config::getGET("order_id");
        $this->customerId = $customerId;
        
        if($customerId && $orderId){
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

            } catch(DBParamException $e){
                $e->handler($e);
            }
        }else{
            header("Location:/html/admin/admin_login.php");
            exit();
        }
    }
    
    public function getOrderHistory(){
        return $this->orderHistoryDto;   
    }   

    public function getOrderDetail(){
        return $this->orderDetailDto;   
    }  
    
    public function getCustomerId(){
        return $this->customerId;   
    }
}
?>