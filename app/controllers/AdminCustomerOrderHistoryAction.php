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
    
class AdminCustomerOrderHistoryAction{
    
    private $orders = [];
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
        $this->customerId = $customerId;
        
        if($customerId){
            try{
                $model = Model::getInstance();
                $pdo = $model->getPdo();
                $orderHistoryDao = new OrderHistoryDao($pdo);

                $orders = $orderHistoryDao->getAllOrderHistory($customerId);
                $this->orders = $orders;

            }catch(DBConnectionException $e){
                $e->handler($e);  

            }catch(MyPDOException $e){
                $e->handler($e);
            }
        }else{
            header("Location:/html/admin/admin_login.php");
            exit();
        }
    }
    
    public function getOrders(){
        return $this->orders;   
    }  
    
    public function getCustomerId(){
        return $this->customerId;   
    }
}
?>