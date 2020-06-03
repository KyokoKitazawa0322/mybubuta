<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;
use \Models\OriginalException;
use \Config\Config;
use \Models\CommonValidator;

class MyPageUpdateCompleteAction {

    
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "do_logout"){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION['customer_id'])){
            header('Location:/html/login.php');   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
    
        /*====================================================================
         mypage_update_confirm.phpで「登録する」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "do_register" && $_SESSION['update']){
            
            $password = $_SESSION['update']['password'];
            $lastName = $_SESSION['update']['last_name'];
            $firstName = $_SESSION['update']['first_name'];
            $rubyLastName = $_SESSION['update']['ruby_last_name'];
            $rubyFirstName = $_SESSION['update']['ruby_first_name'];
            $zipCode01 = $_SESSION['update']['zip_code_01'];
            $zipCode02 = $_SESSION['update']['zip_code_02'];
            $prefecture = $_SESSION['update']['prefecture'];
            $city = $_SESSION['update']['city'];
            $blockNumber = $_SESSION['update']['block_number'];
            $buildingName = $_SESSION['update']['building_name'];
            $tel = $_SESSION['update']['tel'];
            $mail = $_SESSION['update']['mail'];

            try {
                $customerDao = new CustomerDao();
                $customerDao->updateCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $mail, $customerId);
                
            } catch(\PDOException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                header('Content-Type: text/plain; charset=UTF-8', true, 500);
                die('エラー:データベースの処理に失敗しました。');
                
            }catch(OriginalException $e){
                Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
                header('Content-Type: text/plain; charset=UTF-8', true, 400);
                die('エラー:'.$e->getMessage());
            }
            
            unset($_SESSION['update']);
            unset($_SESSION['password_input']);
            unset($_SESSION['update_data']);
            
            /*——————————————————————————————————————————————————————————————
             order_delivery_listからきた場合の処理
            ————————————————————————————————————————————————————————————————*/
       
            if(isset($_SESSION['from_order_flag'])){
                unset($_SESSION['from_order_flag']);
                header('Location:/html/order/order_delivery_list.php');
                exit();
            }
            /*——————————————————————————————————————————————————————————————*/
            
        }else{
            header('Location:/html/mypage/mypage_update.php');
            exit();
        }
    }
}
?>