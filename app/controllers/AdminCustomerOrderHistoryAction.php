<?php
namespace Controllers;
use \Models\OrderHistoryDao;
use \Models\OrderHistoryDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;
    
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

            $orderHistoryDao = new OrderHistoryDao();

            try{
                $orders = $orderHistoryDao->getAllOrderHistory($customerId);
                $this->orders = $orders;

                } catch(MyPDOException $e){
                    $e->handler($e);
                }
            }
        }
    
    public function getOrders(){
        return $this->orders;   
    }  
}
?>