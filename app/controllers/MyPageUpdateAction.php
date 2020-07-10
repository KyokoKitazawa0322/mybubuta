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
    private $oldPasswordError = false;
    private $passwordError = false;
    private $passwordConfirmError = false;
    
    public function execute(){

        $cmd = filter_input(INPUT_POST, 'cmd');
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
        
        /*——————————————————————————————————————————————————————————————
        　order_deliver_list.phpからの訪問
        ————————————————————————————————————————————————————————————————*/
        
        if($cmd == "from_order"){
            $_SESSION['from_order_flag'] = TRUE;   
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

        /*====================================================================
         「変更内容を確認する」ボタンが押された時の処理
        =====================================================================*/
        if($cmd=="confirm"){
            
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
            $oldPassword = filter_input(INPUT_POST, 'oldPassword');
            $password = filter_input(INPUT_POST, 'password');
            $passwordConfirm = filter_input(INPUT_POST, 'password_confirm');

            /*- パスワード、パスワード確認ともに入力がなければ、
            ログイン時にセットしたクッキー値を格納しバリデーションを通す。(変更なしとみなす) -*/
            if(!$password && !$passwordConfirm){
                $oldPassword = $_COOKIE['password'];
                $password = $_COOKIE['password'];
                $passwordConfirm = $_COOKIE['password'];
            }else{
                $_SESSION['password_input'] = "is";
            }
            
            $validator = new CommonValidator();
            
            $_SESSION['update'] = array(
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
             'oldPassword' => $oldPassword,
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
            if(!password_verify($password, $hashPassword)){
                $this->oldPasswordError = "パスワードが間違ってます。";
            }

            $key="新しいパスワード";
            $this->passwordError = $validator->passValidation($key, $password);

            $key="新しいパスワード(再確認)";  
            $this->passwordConfirmError = $validator->passConfirmValidation($key, $passwordConfirm, $password);

            if($validator->getResult() && !($this->mailError) && !($this->oldPasswordError)) {
                $_SESSION['update_data'] = "complete";
                header('Location:/html/mypage/update/mypage_update_confirm.php');
                exit();
            }else{
                $_SESSION['update_data'] = "incomplete";
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
        if(isset($_SESSION['update']['prefecture'])){
            if($_SESSION['update']['prefecture']==$value){ 
                return true;
            }
        }elseif($customerData==$value){
            return true;    
        }
    }
    
    public function echoValue($value, $customerData){
        if(isset($_SESSION['update'][$value])){
            echo $_SESSION['update'][$value];
        }else{
            echo $customerData;
        }
    }
}
?>