<?php
namespace Controllers;

use \Models\InvalidParamException;

class MyPageUpdateConfirmAction extends \Controllers\CommonMyPageAction{

    
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        
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
        
        if(!isset($_SESSION['update_data']) || $_SESSION['update_data'] != "complete"){
            if(!isset($_SESSION['update_data'])){
                $updateData = "nothing";
            }else{
                $updateData = $_SESSION['update_data'];   
            }
            throw new InvalidParamException('Invalid param for update_confirm:$_SESSION["update_data"]='.$updateData);
        }
    }
}
?>