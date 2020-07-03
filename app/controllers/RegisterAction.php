<?php
namespace Controllers;
use \Models\CommonValidator;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Config\Config;

class RegisterAction{
    
    private $lastNameError = false;
    private $firstNameError = false;
    private $rubyLastNameError = false;
    private $rubyFirstNameError = false;
    private $zipCode01Error = false;
    private $zipCode02Error = false;
    private $prefectureError = false;
    private $cityError = false;
    private $blockNumberError = false;
    private $telError = false;
    private $mailError = false;
    private $passwordError = false;
    private $passwordConfirmError = false;
        
        
    public function execute(){

        $cmd = filter_input(INPUT_POST, 'cmd');
        
        /*====================================================================
        　「会員登録をする」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "confirm"){
            
            $lastName = filter_input(INPUT_POST, 'last_name');
            $firstName = filter_input(INPUT_POST, 'first_name');
            $rubyLastName = filter_input(INPUT_POST, 'ruby_last_name');
            $rubyFirstName = filter_input(INPUT_POST, 'ruby_first_name');
            $zipCode01 = filter_input(INPUT_POST, 'zip_code_01');
            $zipCode02 = filter_input(INPUT_POST, 'zip_code_02');
            $prefecture = filter_input(INPUT_POST, 'prefecture');
            $city = filter_input(INPUT_POST, 'city');
            $blockNumber = filter_input(INPUT_POST, 'block_number');
            $buildingName = filter_input(INPUT_POST, 'building_name');
            $tel = filter_input(INPUT_POST, 'tel');
            $mail = filter_input(INPUT_POST, 'mail');
            $password = filter_input(INPUT_POST, 'password');
            $passwordConfirm = filter_input(INPUT_POST, 'passwordConfirm');

            $validator = new CommonValidator();
            
            $_SESSION['register'] = array(
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
             'password' => $password,
             'password_confirm' => $passwordConfirm
            );

            $key = "氏名(性)";
            $this->lastNameError = $validator->fullWidthValidation($key, $lastName);

            $key = "氏名(名)";
            $this->firstNameError = $validator->fullWidthValidation($key, $firstName);

            $key = "氏名(セイ)";
            $this->rubyLastNameError = $validator->rubyValidation($key, $rubyLastName);

            $key = "氏名(メイ)";
            $this->rubyFirstNameError = $validator->rubyValidation($key, $rubyFirstName);

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
            $this->cityError = $validator->requireCheck($key, $city);

            $key="番地";
            $this->blockNumberError = $validator->requireCheck($key, $blockNumber);
            
            $customerMail = "";
            try{
                $this->mailError = $validator->checkMail($mail, $customerMail);
                
            } catch(MyPDOException $e){
                $e->handler($e);

            }
            
            /*- 他者とのメールアドレスの重複がなければ続けてバリデーションチェック -*/
            if(!$this->mailError){
                $key="メールアドレス";
                $this->mailError = $validator->mailValidation($key, $mail);
            }
                
            $key="電話番号";
            $this->telError = $validator->telValidation($key, $tel);

            $key="パスワード";
            $this->passwordError = $validator->passValidation($key, $password);

            $key="パスワード(再確認)";
            $this->passwordConfirmError = $validator->passConfirmValidation($key, $passwordConfirm, $password);

            
            if($validator->getResult()) {
                $_SESSION['register_data'] = "complete";
                header('Location:/html/register/register_confirm.php');
                exit();
            }else{
                $_SESSION['register_data'] = "incomplete";
            }
        }
    }
    
    /*---------------------------------------*/
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

    public function getTelError(){
        return $this->telError;   
    }
    
    public function getMailError(){
        return $this->mailError;   
    }
    
    public function getPasswordError(){
        return $this->passwordError;   
    }
    
    public function getPasswordConfirmError(){
        return $this->passwordConfirmError;   
    }

    public function checkSelectedPrefecture($value){
        if(isset($_SESSION['admin_update']['prefecture']) && $_SESSION['admin_update']['prefecture']==$value){ 
            return true;
        }
    }
    
    public function echoValue($value){
        if(isset($_SESSION['register'][$value])){
            echo $_SESSION['register'][$value];
        }
    }
}


?>    

   