<?php
namespace Controllers;

use \Models\DeliveryDao;
use \Models\CustomerDao;    

use \Config\Config;
use \Models\CsrfValidator;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;

class MyPageDeliveryCompleteAction extends \Controllers\CommonMyPageAction{
    
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];     

        /*====================================================================
         mypage_delivery_entry_confirm.phpで「この内容で登録をする」ボタンが押された時の処理
         =====================================================================*/
        
        $token = filter_input(INPUT_POST, "token_del_entry_complete");
        $formName = "token_del_entry_complete";
        
        try{
            CsrfValidator::checkToken($token, $formName);
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }

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

        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $deliveryDao = new DeliveryDao($pdo);
            $deliveryDao->updateDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $customerId, $deliveryId);

            unset($_SESSION['delivery_entry_data']);
            unset($_SESSION['del_update']);
            unset($_SESSION['del_id']);

        }catch(DBConnectionException $e){
            $e->handler($e);  
            
        } catch(MyPDOException $e){
            $e->handler($e);

        }catch(DBParamException $e){
            $e->handler($e);
        }

        /*——————————————————————————————————————————————————————————————
            order_delivery_listからきた場合
        ————————————————————————————————————————————————————————————————*/

        if(isset($_SESSION['from_order_flag'])){
            unset($_SESSION['from_order_flag']);
            header('Location:/html/order/order_delivery_list.php');
            exit();
        }
    }
}
?>