<?php
namespace Controllers;

use \Models\InvalidParamException;

class MyPageDeliveryAddConfirmAction extends \Controllers\CommonMyPageAction{
    
    public function execute(){
        
        $cmd = filter_input(INPUT_GET, 'cmd');
        
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
        
        if(!isset($_SESSION['add_data']) || $_SESSION['add_data'] != "complete"){
            if(!isset($_SESSION['add_data'])){
                $addData = "nothing";
            }else{
                $addData = $_SESSION['add_data'];   
            }
            throw new InvalidParamException('Invalid param for update_confirm:$_SESSION["add_data"]='.$addData);
        }
    }
}
?>