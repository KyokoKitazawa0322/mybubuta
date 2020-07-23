<?php
namespace Controllers;
use \Models\NoticeDao;
use \Models\NoticeDto;
use \Models\Model;

use \Models\CommonValidator;
use \Models\CsrfValidator;
use \Config\Config;

use \Models\InvalidParamException;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;
    
class AdminNoticeRegisterAction{
    
    private $item;
    
    private $titleError;
    private $mainTextError;
    
    private $sessionKey;
    private $errorMessage = "none";
    
    public function execute(){
        
        /*====================================================================
      　  $_SESSION['admin_id']がなければadmin_login.phpへリダイレクト
        =====================================================================*/
        $cmd = Config::getPOST("cmd");
        
        if($cmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }
        
        $sessionKey = "admin_notice_register-".rand(5,10);
        $this->sessionKey = $sessionKey;
        
        $password = Config::getPOST("password");
        
        if($cmd == "admin_notice_register"){
            
            $token = Config::getPOST( "token");
            try{
                CsrfValidator::validate($token);
            }catch(InvalidParamException $e){
                $e->handler($e);   
            }
            
            $title = Config::getPOST('title'); 
            $mainText = Config::getPOST('main_text'); 
            
            $_SESSION[$sessionKey] = array (
                'title' => $title,    
                'mainText' => $mainText
            );
            
            $validator = new CommonValidator();
            
            $key = "件名";
            $limit = 50;
            $this->titleError = $validator->textAreaValidation($key, $title, $limit); 
            
            $key = "本文";
            $limit = 1000;
            $this->mainTextError = $validator->textAreaValidation($key, $mainText, $limit); 
                
            if($validator->getResult()){
                $confirmPassword = getenv('CONFIRM_PASSWORD');
                
                if($password !== $confirmPassword){
                    try{
                        $model = Model::getInstance();
                        $pdo = $model->getPdo();
                        $noticeDao = new NoticeDao($pdo);
                        $noticeDto = $noticeDao->insertNoticeInfo($title, $mainText);
                        
                        unset($_SESSION[$sessionKey]);

                    }catch(DBConnectionException $e){
                        $e->handler($e);   

                    } catch(MyPDOException $e){
                        $e->handler($e);
                    }
                }else{
                    $this->errorMessage = "デモ画面のため、実際の登録はできません。";
                }
            }
        }
    }
    
    /*---------------------------------------*/
    public function getItem(){
        return $this->noticeDto;   
    }
    
    public function getTitleError(){
        return $this->titleError;   
    }
    
    public function getMailTextError(){
        return $this->mainTextError;   
    }
    
    public function getErrorMessage(){
        return $this->errorMessage;   
    }

    public function echoValue($value){
        $sessionKey = $this->sessionKey;
        if(isset($_SESSION[$sessionKey][$value])){
            echo $_SESSION[$sessionKey][$value];
        }
    }
}
?>