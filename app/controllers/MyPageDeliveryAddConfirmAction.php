<?php
namespace Controllers;

use \Models\InvalidParamException;

class MyPageDeliveryAddConfirmAction extends \Controllers\CommonMyPageAction{
    
    public function execute(){
        
        $cmd = Config::getGET('cmd');
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];
        
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
        
        if(!isset($_SESSION['del_add']['add_data']) || $_SESSION['del_add']['add_data'] != "complete"){
            if(!isset($_SESSION['del_add']['add_data'])){
                $addData = "nothing";
            }else{
                $addData = $_SESSION['del_add']['add_data'];   
            }
            throw new InvalidParamException('Invalid param for update_confirm:$_SESSION["del_add"]["add_data"]='.$addData);
        }
    }
    
    public function echoValue($value){
        if(isset($_SESSION['del_add'][$value])){
            echo $_SESSION['del_add'][$value];
        }
    }
}
?>