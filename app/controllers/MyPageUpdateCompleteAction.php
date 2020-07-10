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
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
    
        /*====================================================================
         mypage_update_confirm.phpで「登録する」ボタンが押された時の処理
        =====================================================================*/

        $token = filter_input(INPUT_POST, "token_update_complete");
        $formName = "token_update_complete";
        
        try{
            CsrfValidator::checkToken($token, $formName);
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }

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
    }
}
?>