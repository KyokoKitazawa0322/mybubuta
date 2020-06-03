<?php
namespace Controllers;
use \Models\DeliveryDao;
use \Models\OriginalException;
use \Config\Config;

class MyPageDeliveryAddCompleteAction {
    
    public function execute(){
        
        $cmd = filter_input(INPUT_GET, 'cmd');
        
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
            「配送先保存ボタン」が押された時の処理
        =====================================================================*/
        
        if(isset($_SESSION['add_data']) && $_SESSION['add_data'] == "clear") { 
            
            $lastName = $_SESSION['del_add']['last_name'];
            $firstName = $_SESSION['del_add']['first_name'];
            $rubyLastName = $_SESSION['del_add']['ruby_last_name'];
            $rubyFirstName = $_SESSION['del_add']['ruby_first_name'];
            $zipCode01 = $_SESSION['del_add']['zip_code_01'];
            $zipCode02 = $_SESSION['del_add']['zip_code_02'];
            $prefecture = $_SESSION['del_add']['prefecture'];
            $city = $_SESSION['del_add']['city'];
            $blockNumber = $_SESSION['del_add']['block_number'];
            $buildingName = $_SESSION['del_add']['building_name'];
            $tel = $_SESSION['del_add']['tel'];
            
            try{
                $deliveryDao = new DeliveryDao();
                
                $deliveryDao->insertDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $customerId);
                
            } catch(\PDOException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');

            }catch(OriginalException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }

            unset($_SESSION['add_data']);
            unset($_SESSION['del_add']);
            
            /*——————————————————————————————————————————————————————————————
                order_delivery_listからきた場合
            ————————————————————————————————————————————————————————————————*/
            
            if(isset($_SESSION['from_order_flag'])){
                unset($_SESSION['from_order_flag']);
                header('Location:/html/order/order_delivery_list.php');
                exit();
            }
        }else{
            header("Location:/html/login.php");   
            exit();
        }
    }
}
?>