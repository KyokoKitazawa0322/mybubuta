<?php
namespace Controllers;

use \Models\InvalidParamException;

class MyPageUpdateConfirmAction extends \Controllers\CommonMyPageAction{
    
    public function execute(){
        
        $cmd = Config::getPOST("cmd");
        
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
        
        if(!isset($_SESSION['mypage_update']['status']) || $_SESSION['mypage_update']['status'] != "complete"){
            if(!isset($_SESSION['mypage_update']['status'])){
                $updateStatus = "nothing";
            }else{
                $updateStatus = $_SESSION['mypage_update']['status'];   
            }
            throw new InvalidParamException('Invalid param for update_confirm:$_SESSION["mypage_update"]["status"]='.$updateStatus);
        }
    }
    
    public function echoValue($value){
        if(isset($_SESSION['mypage_update'][$value])){
            echo $_SESSION['mypage_update'][$value];
        }
    }
    
    public function checkValue($value){
        if(isset($_SESSION['mypage_update'][$value]) && $_SESSION['mypage_update'][$value]){
            return TRUE;   
        }else{
            return FALSE;
        }
    }
    
    public function CountPassword($mark){
        $password = $_SESSION['mypage_update']['password'];
        for($i = 0; $i < strlen($password); $i++){
            echo $mark;   
        }
    }
}
?>