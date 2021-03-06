<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomerDto;
use \Models\DeliveryDao;
use \Models\DeliveryDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;
    
class AdminCustomerDetailAction{
    
    private $customerDto;
    private $customerDeliveryDto;
        
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
        
        if($customerId){
            try{
                $model = Model::getInstance();
                $pdo = $model->getPdo();
                $customerDao = new CustomerDao($pdo);
                $deliveryDao = new DeliveryDao($pdo);

                $this->customerDto = $customerDao->getCustomerById($customerId);
                $this->customerDeliveryDto = $deliveryDao->getDeliveryInfo($customerId);

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
    
    public function getCustomer(){
        return $this->customerDto;   
    }
    
    public function getCustomerDelivery(){
        return $this->customerDeliveryDto;   
    }
}
?>