<?php
namespace Controllers;
use \Models\OrderDetailDao;
use \Models\OrderDetailDto;
use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;
    
class AdminCustomerOrderDetailAction{
    
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

            $orderHistoryDao = new OrderHistoryDao();
            $orderDetailDao = new OrderDetailDao();

            try{
                $this->orderHistoryDto = $orderHistoryDao->getOrderHistory($customerId, $orderId);
                $this->orderDetailDto = $orderDetailDao->getOrderDetail($orderId);
            } catch(MyPDOException $e){
                $e->handler($e);

            } catch(DBParamException $e){
                $e->handler($e);
            }
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