<?php
namespace Controllers;

use \Models\DeliveryDao;
use \Models\CustomerDao;    
use \Models\Model;

use \Config\Config;
use \Models\CsrfValidator;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class MyPageDeliveryCompleteAction extends \Controllers\CommonMyPageAction{
    
    private $keyForUpdate;
        
    public function execute(){
        
        $cmd = Config::getPOST("cmd");
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];     

        /*====================================================================
         mypage_delivery_entry_confirm.phpで「この内容で登録をする」ボタンが押された時の処理
         =====================================================================*/
        
        $token = Config::getPOST( "token_del_entry_complete");
        $formName = "token_del_entry_complete";
        
        try{
            CsrfValidator::checkToken($token, $formName);
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }

        $delId = Config::getGET('del_id');
        $keyForUpdate = "del_update-".$delId;
        
        try{    
            if(isset($_SESSION[$keyForUpdate])){
                $this->keyForUpdate = $keyForUpdate;
            }else{
                throw new InvalidParamException('Invalid param for $keyForUpdate='.$keyForUpdate);
            }
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }
        
        
        $lastName = $_SESSION[$keyForUpdate]['last_name'];
        $firstName = $_SESSION[$keyForUpdate]['first_name'];
        $rubyLastName = $_SESSION[$keyForUpdate]['ruby_last_name'];
        $rubyFirstName = $_SESSION[$keyForUpdate]['ruby_first_name'];
        $zipCode01 = $_SESSION[$keyForUpdate]['zip_code_01'];
        $zipCode02 = $_SESSION[$keyForUpdate]['zip_code_02'];
        $prefecture = $_SESSION[$keyForUpdate]['prefecture'];
        $city = $_SESSION[$keyForUpdate]['city'];
        $blockNumber = $_SESSION[$keyForUpdate]['block_number'];
        $buildingName = $_SESSION[$keyForUpdate]['building_name'];
        $tel = $_SESSION[$keyForUpdate]['tel'];

        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $deliveryDao = new DeliveryDao($pdo);
            $deliveryDao->updateDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $customerId, $delId);

            unset($_SESSION[$keyForUpdate]);

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

        if(isset($_SESSION['track_for_order']) && $_SESSION['track_for_order']=="order_delivery_list"){
            unset($_SESSION['track_for_order']);
            header('Location:/html/order/order_delivery_list.php');
            exit();
        }
    }
}
?>