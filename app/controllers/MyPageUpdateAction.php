<?php
namespace Controllers;

use \Models\CustomerDao;
use \Models\CustomersDto;
use \Models\Model;

use \Config\Config;

use \Models\CommonValidator;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class MyPageUpdateAction extends \Controllers\CommonMyPageAction{

    private $customerDto;
    
    private $message;
    
    private $lastNameError = false;
    private $firstNameError = false;
    private $rubyLastNameError = false;
    private $rubyFirstNameError = false;
    private $zipCode01Error = false;
    private $zipCode02Error = false;
    private $prefectureError = false;
    private $cityError = false;
    private $blockNumberError = false;
    private $buildingNameError = false;
    private $telError = false;
    private $mailError = false;
    private $oldPasswordError = false;
    private $passwordError = false;
    private $passwordConfirmError = false;
    
    public function execute(){

        $cmd = Config::getPOST("cmd");
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
        
        /*——————————————————————————————————————————————————————————————
        　order_delivery_list.phpからの訪問
        ————————————————————————————————————————————————————————————————*/
        
        if($cmd == "from_order"){
            $_SESSION['track_for_order'] = "order_delivery_list";  
        }
        /*—————————————————————————————————————————————————————————————— */
        
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $customerDao = new CustomerDao($pdo);
            $this->customerDto = $customerDao->getCustomerById($customerId);
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
            
        } catch(MyPDOException $e){
            $e->handler($e);

        }catch(DBParamException $e){
            $e->handler($e);
        }
       
        $customerMail = $this->customerDto->getMail();

        //デモ用アカウントのためパスワードとメールアドレスを変更できない趣旨のメッセージを出力
        if($customerMail == "hanako@yahoo.co.jp"){
            $this->message = "このアカウントはデモ用のためパスワード及びメールアドレスの変更はできません。";
        }else{
            $this->message = "none";   
        }        /*====================================================================
         「変更内容を確認する」ボタンが押された時の処理
        =====================================================================*/
        if($cmd=="confirm"){
            $_SESSION['mypage_update'] = array();
                
            $lastName = Config::getPOST('last_name');
            $firstName = Config::getPOST('first_name');
            $rubyLastName = Config::getPOST('ruby_last_name');
            $rubyFirstName = Config::getPOST('ruby_first_name');
            $zipCode01 = Config::getPOST('zip_code_01');
            $zipCode02 = Config::getPOST('zip_code_02');
            $prefecture = Config::getPOST('prefecture');
            $city = Config::getPOST('city');
            $blockNumber = Config::getPOST('block_number');
            $buildingName = Config::getPOST('building_name');
            $tel = Config::getPOST('tel');
            $mail = Config::getPOST('mail');
            $oldPassword = Config::getPOST('old_password');
            $password = Config::getPOST('password');
            $passwordConfirm = Config::getPOST('password_confirm');
            
            /*- パスワード、パスワード確認ともに入力がなければ、
            ログイン時にセットしたクッキー値を格納しバリデーションを通す。(変更なしとみなす) -*/
            //デモ用アカウントのため変更しない
            if(!$password && !$passwordConfirm || $oldPassword == "hanako875"){
                $oldPassword = $_COOKIE['password'];
                $password = $_COOKIE['password'];
                $passwordConfirm = $_COOKIE['password'];
                $_SESSION['mypage_update']['password_input'] = FALSE; 
    
            }else{
                $_SESSION['mypage_update']['password_input'] = TRUE; 
            }
            
            $validator = new CommonValidator();
            
            $_SESSION['mypage_update'] += array(
             'last_name' => $lastName,
             'first_name' => $firstName,
             'ruby_last_name' => $rubyLastName,
             'ruby_first_name' => $rubyFirstName,
             'zip_code_01' => $zipCode01,
             'zip_code_02' => $zipCode02,
             'prefecture' => $prefecture,
             'city' => $city,
             'block_number' => $blockNumber,
             'building_name' => $buildingName,
             'tel' => $tel,
             'mail' => $mail,
             'old_password' => $oldPassword,
             'password' => $password,
             'password_confirm' => $passwordConfirm
            );
            
            $customerMail = $this->customerDto->getMail();
            
            //デモ用アカウントのため変更しない
            if($customerMail == "hanako@yahoo.co.jp"){
                $_SESSION['mypage_update']['mail'] = $customerMail;
            }

            $key = "氏名(性)";
            $limit = 20;
            $this->lastNameError = $validator->fullWidthValidation($key, $lastName, $limit);

            $key = "氏名(名)";
            $limit = 20;
            $this->firstNameError = $validator->fullWidthValidation($key, $firstName, $limit);

            $key = "氏名(セイ)";
            $limit = 20;
            $this->rubyLastNameError = $validator->rubyValidation($key, $rubyLastName, $limit);

            $key = "氏名(メイ)";
            $limit = 20;
            $this->rubyFirstNameError = $validator->rubyValidation($key, $rubyFirstName, $limit);

            $key = "郵便番号(3ケタ)";
            $this->zipCode01Error = $validator->firstZipCodeValidation($key, $zipCode01);

            $key = "郵便番号(4ケタ)";
            $this->zipCode02Error  = $validator->lastZipCodeValidation($key, $zipCode02);

            $key="都道府県";
            $this->prefectureError = $validator->requireCheck($key, $prefecture);
            
            if(!$this->prefectureError){
                try{
                    $validator->checkPrefecture($prefecture);
                }catch(InvalidParamException $e){
                    $e->handler($e);   
                }
            }

            $key="市区町村";
            $limi = 30;
            $this->cityError = $validator->fullWidthValidation($key, $city, $limit);

            $key="番地";
            $limit = 30;
            $this->blockNumberError = $validator->fullWidthValidation($key, $blockNumber, $limit);
            
            $key="建物名等";
            $limit = 30;
            $this->buildingNameError = $validator->fullWidthValidation($key, $buildingName, $limit);

            $key="メールアドレス";
            $this->mailError = $validator->mailValidation($key, $mail);
            
            if(!$this->mailError){
            
                $customerMail = $this->customerDto->getMail();
            
                try{    
                    $mailExists = $customerDao->checkMailExistsForUpdate($mail, $customerMail);
                    if($mailExists){
                        $this->mailError = "既に使用されているメールアドレスです。";
                    } 
                }catch(DBConnectionException $e){
                    $e->handler($e);   
                
                } catch(MyPDOException $e){
                    $e->handler($e);
                }
            }

            $key="電話番号";
            $this->telError = $validator->telValidation($key, $tel);
            
            //現在のパスワードの一致確認/
            $hashPassword = $this->customerDto->getHashPassWord();
            if(!password_verify($oldPassword, $hashPassword)){
                $this->oldPasswordError = "パスワードが間違ってます。";
            }

            $key="新しいパスワード";
            $this->passwordError = $validator->passValidation($key, $password);

            $key="新しいパスワード(再確認)";  
            $this->passwordConfirmError = $validator->passConfirmValidation($key, $passwordConfirm, $password);

            if($validator->getResult() && !($this->mailError) && !($this->oldPasswordError)) {
                $_SESSION['mypage_update']['status'] = "complete";
                header('Location:/html/mypage/update/mypage_update_confirm.php');
                exit();
            }else{
                $_SESSION['mypage_update']['status'] = "incomplete";
            }
        }
    }
        
    /*---------------------------------------*/
    public function getMessage(){
        return $this->message;    
    }
    
    public function getLastNameError(){
        return $this->lastNameError;   
    }
    
    public function getFirstNameError(){
        return $this->firstNameError;   
    }
    
    public function getRubyLastNameError(){
        return $this->rubyLastNameError;   
    }
    
    public function getRubyFirstNameError(){
        return $this->rubyFirstNameError;   
    }
    
    public function getZipCode01Error(){
        return $this->zipCode01Error;   
    }
    
    public function getZipCode02Error(){
        return $this->zipCode02Error;   
    }
   
    public function getPrefectureError(){
        return $this->prefectureError;   
    }
    
    public function getCityError(){
        return $this->cityError;   
    }
    
    public function getBlockNumberError(){
        return $this->blockNumberError;   
    }
    
    public function getBuildingNameError(){
        return $this->buildingNameError;   
    }
    
    public function getTelError(){
        return $this->telError;   
    }
    
    public function getMailError(){
        return $this->mailError;   
    }
    
    public function getOldPasswordError(){
        return $this->oldPasswordError;   
    }
    
    public function getPasswordError(){
        return $this->passwordError;   
    }
    
    public function getPasswordConfirmError(){
        return $this->passwordConfirmError;   
    }
    
    public function getCustomerDto(){
        return $this->customerDto;   
    }
    
    public function checkSelectedPrefecture($value, $customerData){
        if(isset($_SESSION['mypage_update']['prefecture'])){
            if($_SESSION['mypage_update']['prefecture']==$value){ 
                return true;
            }
        }elseif($customerData==$value){
            return true;    
        }
    }
    
    public function echoValue($value, $customerData){
        if(isset($_SESSION['mypage_update'][$value])){
            echo $_SESSION['mypage_update'][$value];
        }else{
            echo $customerData;
        }
    }
    
    public function echoValueForPassWord($value, $customerData){
        if(isset($_SESSION['mypage_update']['password_input']) && $_SESSION['mypage_update']['password_input']){
            if(isset($_SESSION['mypage_update'][$value])){
                echo $_SESSION['mypage_update'][$value];
            }else{
                echo $customerData;
            }
        }
    }
}
?>