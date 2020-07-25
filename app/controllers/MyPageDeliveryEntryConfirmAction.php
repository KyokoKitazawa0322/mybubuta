<?php
namespace Controllers;

use \Models\InvalidParamException;

class MyPageDeliveryEntryConfirmAction extends \Controllers\CommonMyPageAction{
    
    private $keyForUpdate;
    private $delId;
    
    public function execute(){
        
        $postCmd = Config::getPOST('cmd');
        
        $this->checkLogoutRequest($postCmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];
        
        $delId = Config::getGET('del_id');       
        $this->delId = $delId;   
        $keyForUpdate = "del_update-".$delId;
        $this->keyForUpdate = $keyForUpdate;
        
        try{
            $this->checkValidationResult();
            
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
        $keyForUpdate = $this->keyForUpdate;
        if(!isset($_SESSION[$keyForUpdate]['delivery_entry_data']) || $_SESSION[$keyForUpdate]['delivery_entry_data'] != "complete"){
            if(!isset($_SESSION[$keyForUpdate]['delivery_entry_data'])){
                $deliveryEntryData = "nothing";
            }else{
                $deliveryEntryData = $_SESSION[$keyForUpdate]['delivery_entry_data'];   
            }
            throw new InvalidParamException('Invalid param for update_confirm:$_SESSION[$keyForUpdate]["delivery_entry_data"]='.$deliveryEntryData);
        }
    }
    
    public function getDelId(){
        return $this->delId;   
    }
    
    public function echoValue($value){
        $keyForUpdate = $this->keyForUpdate;
        if(isset($_SESSION[$keyForUpdate][$value])){
            echo $_SESSION[$keyForUpdate][$value];
        }else{
            echo $customerDate;
        }
    }
}
?>