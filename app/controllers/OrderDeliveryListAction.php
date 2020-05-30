<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomerDto;
use \Models\DeliveryDao;
use \Models\DeliveryDto;

class OrderDeliveryListAction{

    private $customer;
    private $delivery;
    
    public function execute(){
        
        $customerDao = new CustomerDao();
        $deliveryDao = new DeliveryDao();

         if(!isset($_SESSION['customer_id'])){
            header('Location:/html/login.php');
            exit();
        }
        

        //登録情報を取得
        $customerDto = $customerDao->getCustomerById($_SESSION['customer_id']);
        $this->customer = $customerDto;
        

        //削除ボタンがおされたときの処理
        if(isset($_POST['delete'])){
            $deliveryDao->deleteDeliveryInfo($_SESSION['customer_id'], $_POST['del_id']);
        }
    
        //配送先情報の取得
        $deliveryDto = $deliveryDao->getDeliveryInfo($_SESSION['customer_id']);
        $this->delivery = $deliveryDto;
    }

    public function getCustomer(){
        return $this->customer;
    }
    
    public function getDelivery(){
        return $this->delivery;   
    }
    
    //表示画面で検証しtrueであればchecked="checked"を出力
    public function checkCustomer($customer){
        if(isset($_SESSION['def_addr'])){
            if($_SESSION['def_addr']=="1"){
                return true;
            }
        }elseif($customer->getDelFlag() == 0){
            return true;
        }else{
            return false;
        }
    }
        
    //表示画面で検証しtrueであればchecked="checked"を出力
    public function checkDelivery($delivery){
        if(isset($_SESSION['def_addr'])){
            if($_SESSION['def_addr'] == $delivery->getDeliveryId()){
                return true;
            }
        }elseif($delivery->getDelFlag() == 0){ 
            return true;
        }else{
            return false;
        }
    }
}

?>    

   