<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomerDto;
use \Models\DeliveryDao;
use \Models\DeliveryDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;
    
class AdminCustomerDetailAction{
    
    private $customerDto;
    private $customerDeliveryDto;
        
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
        
        if($cmd == "detail"){
            
            $customerDao = new CustomerDao();
            $deliveryDao = new DeliveryDao();
            $customerId = filter_input(INPUT_POST, 'customer_id');
            $_SESSION['order_customer_id'] = $customerId;
            
            try{
                $customerDto = $customerDao->getCustomerById($customerId);
                $customerDeliveryDto = $deliveryDao->getDeliveryInfo($customerId);
                    
                $this->customerDto = $customerDto;
                $this->customerDeliveryDto = $customerDeliveryDto;
                
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
    
    public function getCustomer(){
        return $this->customerDto;   
    }
    
    public function getCustomerDelivery(){
        return $this->customerDeliveryDto;   
    }
}
?>