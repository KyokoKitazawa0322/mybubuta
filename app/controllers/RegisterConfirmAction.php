<?php
namespace Controllers;

use \Models\InvalidParamException;

class RegisterConfirmAction{
        
    public function execute(){
        
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
        
        if(!isset($_SESSION['register_data']) || $_SESSION['register_data'] != "complete"){
            if(!isset($_SESSION['register_data'])){
                $registerData = "nothing";
            }else{
                $registerData = $_SESSION['register_data'];   
            }
            throw new InvalidParamException('Invalid param for update_confirm:$_SESSION["register_data"]='.$registerData);
        }
    }
    
    /*---------------------------------------*/
    //トークンをセッションにセット
    public function setToken(){
        $token = sha1(uniqid(mt_rand(), true));
        $_SESSION['token']['complete'] = $token;
    }
}