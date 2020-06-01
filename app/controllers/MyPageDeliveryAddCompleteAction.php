<?php
namespace Controllers;
use \Models\DeliveryDao;
use \Models\OriginalException;
use \Config\Config;

class MyPageDeliveryAddCompleteAction {
    
    public function execute(){
        
        $cmd = filter_input(INPUT_GET, 'cmd');
        
        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }

        //配送先保存ボタンが押された時の処理
        if(isset($_SESSION['add_data']) && $_SESSION['add_date'] == "clear") { 
            
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
            
            try{
                $deliveryDao->insertDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $customerId);
                
            } catch(\PDOException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');

            }catch(OriginalException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }

            $_SESSION['add_data'] = NULL;
            $_SESSION['del_add'] = NULL;

            //order_delivery_listからきた場合
            if(isset($_SESSION['from_order_flag'])){
                header('Location:/html/order/order_delivery_list.php');
                unset($_SESSION['from_order_flag']);
                exit();
            }
        }
    }
}
?>