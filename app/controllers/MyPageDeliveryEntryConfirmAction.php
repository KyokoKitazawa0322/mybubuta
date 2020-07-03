<?php
namespace Controllers;

use \Models\InvalidParamException;

class MyPageDeliveryEntryConfirmAction extends \Controllers\CommonMyPageAction{
    
    public function execute(){
        
        $cmd = filter_input(INPUT_GET, 'cmd');
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];
        
        try{
            $this->checkValidationResult();
            $this->setToken();
            
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }
    }
    
    
    /*---------------------------------------*/
    
    /**
    * バリデーションを通過してきたか確認
    * throw InvalidParamException
    **/
    public function checkValidationResult(){
        
        if(!isset($_SESSION['delivery_entry_data']) || $_SESSION['delivery_entry_data'] != "complete"){
            if(!isset($_SESSION['delivery_entry_data'])){
                $deliveryEntryData = "nothing";
            }else{
                $deliveryEntryData = $_SESSION['delivery_entry_data'];   
            }
            throw new InvalidParamException('Invalid param for update_confirm:$_SESSION["delivery_entry_data"]='.$deliveryEntryData);
        }
    }
    
    /*---------------------------------------*/
    //トークンをセッションにセット
    public function setToken(){
        $token = sha1(uniqid(mt_rand(), true));
        $_SESSION['token']['entry_update_complete'] = $token;
    }
}
?>