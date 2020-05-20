<?php
namespace Controllers;
use \Models\DeliveryDao;
use \Models\CustomerDao;    

class MyPageDeliveryAction {
    
    private $customerDto; 
    private $deliveryDto;
        
    public function execute(){
        
        if(isset($_POST["cmd"]) && $_POST["cmd"] == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }

        $deliveryDao = new DeliveryDao();
        $customerDao = new CustomerDao();
        /**--------------------------------------------------------
           削除ボタンがおされたときの処理
         ---------------------------------------------------------*/

        if(isset($_POST['del_id'])){
            $deliveryId = $_POST['del_id'];   
        }
        
        if(isset($_POST['del_item'])){         
            $deliveryDao->deleteDeliveryInfo($customerId, $deliveryId);

            //全件削除した場合にデフォルト住所を基本登録にもどす
            $deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
            if(!$deliveryDto){
                $deliveryDao->setDeliveryDefault($customerId);
            }
        }
        /**--------------------------------------------------------
           配送先設定ボタンがおされたときの処理
         ---------------------------------------------------------*/
        if(isset($_POST['set'])){
        //会員登録情報であれば値はdef
            if($_POST['del_id']=="def"){
                //customers:del_flag=0(デェフォルト)
                //delivery:del_flag=1に(customer_idで取得した全件対象)
                $customerDao->setDeliveryDefault($customerId);
                $deliveryDao->releaseDeliveryDefault($customerId);
        //配送先登録情報であれば値はdelivery_id
            }else{
                //いつもの配送先に設定されている住所を解除
                $deliveryDao->releaseDeliveryDefault($customerId);
                $customerDao->releaseDeliveryDefault($customerId);
                $deliveryDao->setDeliveryDefault($customerId, $deliveryId);
            }
        }
        //会員登録情報の取得
        $this->customerDto = $customerDao->getCustomerById($customerId);
        //配送先情報の取得（あれば表示）
        $this->deliveryDto = $deliveryDao->getDeliveryInfo($customerId);
    }
    
    public function getCustomerDto(){
        return $this->customerDto;   
    }
    
    public function getDeliveryDto(){
        return $this->deliveryDto;   
    }
}
?>