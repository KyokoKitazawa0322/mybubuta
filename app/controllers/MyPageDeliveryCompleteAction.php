<?php
namespace Controllers;
use \Models\DeliveryDao;
use \Models\CustomerDao;    
use \Models\OriginalException;
use \Config\Config;

class MyPageDeliveryCompleteAction {
    
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "do_logout" ){
            unset($_SESSION['customer_id']);
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }        

        /*====================================================================
            /配送先保存ボタンが押された時の処理
        =====================================================================*/

        if(isset($_SESSION['update_data']) && $_SESSION['update_data'] == "clear"){ 
            
            $lastName = $_SESSION['del_update']['last_name'];
            $firstName = $_SESSION['del_update']['first_name'];
            $rubyLastName = $_SESSION['del_update']['ruby_last_name'];
            $rubyFirstName = $_SESSION['del_update']['ruby_first_name'];
            $zipCode01 = $_SESSION['del_update']['zip_code_01'];
            $zipCode02 = $_SESSION['del_update']['zip_code_02'];
            $prefecture = $_SESSION['del_update']['prefecture'];
            $city = $_SESSION['del_update']['city'];
            $blockNumber = $_SESSION['del_update']['block_number'];
            $buildingName = $_SESSION['del_update']['building_name'];
            $tel = $_SESSION['del_update']['tel'];
            
            $deliveryId = $_SESSION['del_id'];
            $deliveryDao = new DeliveryDao();
            
            try{
                $deliveryDao->updateDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $customerId, $deliveryId);
                
                unset($_SESSION['update_data']);
                unset($_SESSION['del_update']);
                
            } catch(\PDOException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');
                
            }catch(OriginalException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }
    
            /*——————————————————————————————————————————————————————————————
                order_delivery_listからきた場合
            ————————————————————————————————————————————————————————————————*/
            
            if(isset($_SESSION['from_order_flag'])){
                header('Location:/html/order/order_delivery_list.php');
                unset($_SESSION['from_order_flag']);
                exit();
            }
        }else{
            header("Location:/html/login.php");   
            exit();
        }
    }
}
?>