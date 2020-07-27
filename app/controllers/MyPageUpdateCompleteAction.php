<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomersDto;
use \Models\Model;

use \Models\CommonValidator;
use \Models\CsrfValidator;
use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class MyPageUpdateCompleteAction extends \Controllers\CommonMyPageAction{
    
    public function execute(){
        
        $cmd = Config::getPOST("cmd");
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
    
        /*====================================================================
         mypage_update_confirm.phpで「登録する」ボタンが押された時の処理
        =====================================================================*/

        $token = Config::getPOST( "token_update_complete");
        $formName = "token_update_complete";
        
        try{
            CsrfValidator::checkToken($token, $formName);
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }

        $password = $_SESSION['mypage_update']['password'];
        $lastName = $_SESSION['mypage_update']['last_name'];
        $firstName = $_SESSION['mypage_update']['first_name'];
        $rubyLastName = $_SESSION['mypage_update']['ruby_last_name'];
        $rubyFirstName = $_SESSION['mypage_update']['ruby_first_name'];
        $zipCode01 = $_SESSION['mypage_update']['zip_code_01'];
        $zipCode02 = $_SESSION['mypage_update']['zip_code_02'];
        $prefecture = $_SESSION['mypage_update']['prefecture'];
        $city = $_SESSION['mypage_update']['city'];
        $blockNumber = $_SESSION['mypage_update']['block_number'];
        $buildingName = $_SESSION['mypage_update']['building_name'];
        $tel = $_SESSION['mypage_update']['tel'];
        $mail = $_SESSION['mypage_update']['mail'];

        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $customerDao = new CustomerDao($pdo);

            $customerDao->updateCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $mail, $customerId);

        } catch(MyPDOException $e){
            $e->handler($e);

        }catch(DBParamException $e){
            $e->handler($e);
        }

        unset($_SESSION['mypage_update']);

        /*——————————————————————————————————————————————————————————————
         order_delivery_listからきた場合の処理
        ————————————————————————————————————————————————————————————————*/

        if(isset($_SESSION['track_for_order']) && $_SESSION['track_for_order']=="order_delivery_list"){
            unset($_SESSION['track_for_order']);
            header('Location:/html/order/order_delivery_list.php');
            exit();
        }
    }
}
?>