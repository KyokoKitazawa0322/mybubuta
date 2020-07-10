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
        
    public function execute(){
        
        /*====================================================================
      　  $_SESSION['admin_id']がなければadmin_login.phpへリダイレクト
        =====================================================================*/
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }
        
        if($cmd == "order_history"){
            
            $customerId = filter_input(INPUT_POST, 'customer_id');
            $OrderId = filter_input(INPUT_POST, 'order_id');

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
        }
    }
    
    public function getOrders(){
        return $this->orders;   
    }  
}
?>