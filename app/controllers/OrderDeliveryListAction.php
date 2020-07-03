<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomerDto;
use \Models\DeliveryDao;
use \Models\DeliveryDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Config\Config;

class OrderDeliveryListAction{

    private $customerDto;
    private $deliveryDto;
    
    public function execute(){

        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }

        try{
            if(!isset($_SESSION['availableForPurchase'])){
                throw new InvalidParamException('Invalid param for order_confirm_pay_list:$_SESSION["availableForPurchase"]=nothing');
            }
        } catch(InvalidParamException $e){
            $e->handler($e);
        }
            
        $delId = filter_input(INPUT_POST, 'del_id');
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        $customerDao = new CustomerDao();
        $deliveryDao = new DeliveryDao();
        
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

   