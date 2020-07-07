<?php
namespace Controllers;

use \Models\DeliveryDao;

use \Config\Config;
use \Models\CsrfValidator;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;

class MyPageDeliveryAddCompleteAction extends \Controllers\CommonMyPageAction{
    
    public function execute(){
        
        $cmd = filter_input(INPUT_GET, 'cmd');
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
        
        /*====================================================================
         mypage_delivery_add_confirm.phpで「この内容で登録をする」ボタンが押された時の処理
         =====================================================================*/
        

        $token = filter_input(INPUT_POST, "token_del_update_complete");
        $formName = "token_del_update_complete";
        
        try{
            CsrfValidator::checkToken($token, $formName);
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }

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

        } catch(MyPDOException $e){
            $e->handler($e);
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
    }
}
?>