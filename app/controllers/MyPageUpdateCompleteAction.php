<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomersDto;

use \Models\CommonValidator;
use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;

class MyPageUpdateCompleteAction extends \Controllers\CommonMyPageAction{

    
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
    
        /*====================================================================
         mypage_update_confirm.phpで「登録する」ボタンが押された時の処理
        =====================================================================*/

        try{
            $this->checkToken();
            $this->checkValidationResult();
            
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

        try {
            $customerDao = new CustomerDao();

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
    
    //---------------------------------------
    
    //バリデーションを通過してきたか確認
    public function checkValidationResult(){
        if(!isset($_SESSION['update_data']) || $_SESSION['update_data']!=="complete"){
            if(!isset($_SESSION['update_data'])){
                $updateData = "nothing";   
            }else{
                $updateData = $_SESSION['update_data'];
            }
           throw new InvalidParamException('Invalid param for update-complete:$_SESSION["update_data"]='.$updateData);
        }
    }
        
    //---------------------------------------
    
    //トークンをセッションから取得
    public function checkToken(){
        $tokenComplete = filter_input(INPUT_POST, "token_complete");
        //セッションがないか生成したトークンと異なるトークンでPOSTされたときは不正アクセス
        if(!isset($_SESSION['token']['update_complete']) || ($_SESSION['token']['update_complete'] != $tokenComplete)){
            if(!isset($_SESSION['token']['update_complete'])){
                $sessionTokenComplete = "nothing"; 
            }else{
                $sessionTokenComplete = $_SESSION['token']['update_complete'];   
            }
            throw new InvalidParamException('Invalid param for register_complete:$tokenComplete='.$tokenComplete.'/$_SESSION["token"]["update_complete"]='.$sessionTokenComplete);
        }else{
            unset($_SESSION['token']);   
        }
    }
}
?>