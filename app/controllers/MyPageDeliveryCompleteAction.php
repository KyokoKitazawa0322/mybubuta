<?php
namespace Controllers;
use \Models\DeliveryDao;
use \Models\CustomerDao;    

class MyPageDeliveryCompleteAction {
    
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
        
        $deliveryId = $_SESSION['del_id'];
        

        //配送先保存ボタンが押された時の処理
        if(isset($_SESSION['del_update']['input'])){ 
            
            $lastName = $_SESSION['del_update']['last_name'];
            $firstName = $_SESSION['del_update']['first_name'];
            $rubyLastName = $_SESSION['del_update']['ruby_last_name'];
            $rubyFirstName = $_SESSION['del_update']['ruby_first_name'];
            $address01 = $_SESSION['del_update']['address01'];
            $address02 = $_SESSION['del_update']['address02'];
            $address03 = $_SESSION['del_update']['address03'];
            $address04 = $_SESSION['del_update']['address04'];
            $address05 = $_SESSION['del_update']['address05'];
            $address06 = $_SESSION['del_update']['address06'];
            $tel = $_SESSION['del_update']['tel'];
            
            $deliveryDao->updateDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $customerId, $deliveryId);
            
            $_SESSION["update_data"] = NULL;
            
            //order_delivery_listからきた場合
            if(isset($_SESSION['from_order_flag'])){
                header('Location:/html/order/order_delivery_list.php');
                $_SESSION['from_order_flag']=NULL;
                exit;
            }
        }
    }
}
?>