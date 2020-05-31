<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomerDto;
use \Models\DeliveryDao;
use \Models\DeliveryDto;

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
        
        $delId = filter_input(INPUT_POST, 'del_id');
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        $customerDao = new CustomerDao();
        $deliveryDao = new DeliveryDao();
        
        //登録情報を取得
        $customerDto = $customerDao->getCustomerById($customerId);
        $this->customerDto = $customerDto;
        
        //削除ボタンがおされたときの処理
        if($cmd == "delete"){
            $deliveryDao->deleteDeliveryInfo($customerId, $delId);
        }
    
        //配送先情報の取得
        $deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
        $this->deliveryDto = $deliveryDto;
    }

    public function getCustomer(){
        return $this->customerDto;
    }
    
    public function getDelivery(){
        return $this->deliveryDto;   
    }
    
    //0531
    //表示画面で検証しtrueであればchecked="checked"を出力
    public function checkCustomer($customer){
        //order_confirm.phpで選択された場合
        if(isset($_SESSION['def_addr'])){
            if($_SESSION['def_addr'] == "customer"){
                return true;
            }
        //order_confirm.phpを未訪問状態で、かつcustomerテーブルの住所がいつもの配送先に設定されている場
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

   