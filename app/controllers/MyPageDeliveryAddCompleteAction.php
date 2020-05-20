<?php
namespace Controllers;

use \Models\DeliveryDao;

class MyPageDeliveryAddCompleteAction {
    
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

        //配送先保存ボタンが押された時の処理
        if(isset($_SESSION['add_data'])) { 
            
            $deliveryDao = new DeliveryDao();
            $deliveryId = $_SESSION['del_id'];
            
            $lastName = $_SESSION['del_add']['last_name'];
            $firstName = $_SESSION['del_add']['first_name'];
            $rubyLastName = $_SESSION['del_add']['ruby_last_name'];
            $rubyFirstName = $_SESSION['del_add']['ruby_first_name'];
            $address01 = $_SESSION['del_add']['address01'];
            $address02 = $_SESSION['del_add']['address02'];
            $address03 = $_SESSION['del_add']['address03'];
            $address04 = $_SESSION['del_add']['address04'];
            $address05 = $_SESSION['del_add']['address05'];
            $address06 = $_SESSION['del_add']['address06'];
            $tel = $_SESSION['del_add']['tel'];
            
            $deliveryDao->insertDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $customerId);
            
            $_SESSION['add_data']=NULL;
            $_SESSION['del_add']=NULL;

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