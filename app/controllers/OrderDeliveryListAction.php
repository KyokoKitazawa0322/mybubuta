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
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class OrderDeliveryListAction extends \Controllers\CommonMyPageAction{

    private $customerDto;
    private $deliveryDto;
    private $delId;
    
    public function execute(){

        $cmd = Config::getPOST("cmd");
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin(); 
        
        $customerId = $_SESSION['customer_id'];   

        try{
            if(!isset($_SESSION['availableForPurchase'])){
                throw new InvalidParamException('Invalid param for order_confirm_pay_list:$_SESSION["availableForPurchase"]=nothing');
            }
        } catch(InvalidParamException $e){
            $e->handler($e);
        }
            
        $delId = Config::getPOST('del_id');
        $cmd = Config::getPOST("cmd");
        
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $customerDao = new CustomerDao($pdo);
            $deliveryDao = new DeliveryDao($pdo);
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
        }
        
        /*====================================================================
         「削除」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "delete"){
            try{
                $deliveryDao->deleteDeliveryInfo($customerId, $delId);
                
            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(DBParamException $e){
                $e->handler($e);
            }
        }
        /*=============================================================*/
   
        try{
            /*- customerテーブルの登録情報を取得 -*/
            $customerDto = $customerDao->getCustomerById($customerId);
            $this->customerDto = $customerDto;
        
            /*- deliverテーブルの登録情報を取得 -*/
            $deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
            $this->deliveryDto = $deliveryDto;
            
        } catch(MyPDOException $e){
            $e->handler($e);

        }catch(DBParamException $e){
            $e->handler($e);
        }
    }

    public function getCustomer(){
        return $this->customerDto;
    }
    
    public function getDelivery(){
        return $this->deliveryDto;   
    }
    
    public function getDelId(){
        return $this->delId;   
    }
    
    public function checkCustomer($customer){
        if(isset($_SESSION['def_addr'])){
            if($_SESSION['def_addr'] == "customer"){
                echo 'checked="checked"';
            }
        }elseif($customer->getDeliveryFlag()){
            echo 'checked="checked"';
        }
    }
        
    public function checkDelivery($delivery){
        if(isset($_SESSION['def_addr'])){
            if($_SESSION['def_addr'] == $delivery->getDeliveryId()){
                echo 'checked="checked"';
            }
        }elseif($delivery->getDeliveryFlag()){ 
            echo 'checked="checked"';
        }
    }
}

?>    

   