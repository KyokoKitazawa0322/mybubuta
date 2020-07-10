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
    
class AdminOrderDetailAction{
    
    private $orderDetailDto;
    private $orderHistoryDto;
        
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
        
        if($cmd == "admin_order_detail"){            
            $orderId = filter_input(INPUT_POST, 'order_id');
            $customerId = filter_input(INPUT_POST, 'customer_id');
            
            $_SESSION['admin_order_id'] = $orderId;
            $_SESSION['admin_customer_id'] = $customerId;
        }
        
        if(isset($_SESSION['admin_order_id'])){
            $orderId = $_SESSION['admin_order_id'];   
        }
        if(isset($_SESSION['admin_customer_id'])){
            $customerId = $_SESSION['admin_customer_id'];   
        }
        
        if($orderId && $customerId){
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
}
?>