<?php
namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;
use \Models\CommonValidator;
use \Models\OriginalException;
use \Config\Config;

class MyPageUpdateAction {

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
    private $passwordError = false;
    private $passwordConfirmError = false;
    
    public function execute(){

        $cmd = filter_input(INPUT_POST, 'cmd');
        
        if($cmd == "do_logout" ){
            $_SESSION['customer_id'] = null;
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
        
        /*——————————————————————————————————————————————————————————————
        　order_deliver_list.phpからの訪問
        ————————————————————————————————————————————————————————————————*/
        
        if($cmd == "from_order"){
            $_SESSION['from_order_flag']= "is";   
        }
        /*—————————————————————————————————————————————————————————————— */
        
        $customerDao = new CustomerDao();
        try{
            $this->customerDto = $customerDao->getCustomerById($customerId);
        } catch(\PDOException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            die('エラー:データベースの処理に失敗しました。');

        }catch(OriginalException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
            header('Content-Type: text/plain; charset=UTF-8', true, 400);
            die('エラー:'.$e->getMessage());
        }

        /*====================================================================
         「変更内容を確認する」ボタンが押された時の処理
        =====================================================================*/
        if(isset($_POST['cmd']) && $_POST['cmd']=="confirm"){
            
            $validator = new CommonValidator();
            
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
            $passwordConfirm = filter_input(INPUT_POST, 'password_confirm');

            /*- パスワード、パスワード確認ともに入力がなければ、
            ログイン時にセットしたクッキー値を格納しバリデーションを通す。(変更なしとみなす) -*/
            if(!$password && !$passwordConfirm){
                $password = $_COOKIE['password'];
                $passwordConfirm = $_COOKIE['password'];
            }else{
                $_SESSION['password_input'] = "is";
            }

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

            $key="市区町村";
            $this->cityError = $validator->requireCheck($key, $city);

            $key="番地";
            $this->blockNumberError = $validator->requireCheck($key, $blockNumber);

            $key="メールアドレス";
            $this->mailError = $validator->mailValidation($key, $mail);
            
            if(!$this->mailError){
                $ExistingMail = $this->customerDto->getMail();
                try{
                    $this->mailError = $validator->checkMail($mail, $ExistingMail);
                    
                } catch(\PDOException $e){
                    Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
                    header('Content-Type: text/plain; charset=UTF-8', true, 500);
                    die('エラー:データベースの処理に失敗しました。');
                }
            }

            $key="電話番号";
            $this->telError = $validator->telValidation($key, $tel);

            $key="パスワード";
            $this->passwordError = $validator->passValidation($key, $password);

            $key="パスワード(再確認)";
            $this->passwordConfirmError = $validator->passConfirmValidation($key, $passwordConfirm, $password);

            if($validator->getResult()) {
                $_SESSION['update_data'] = "clear";
                header('Location:/html/mypage/mypage_update_confirm.php');
                exit();
            }
        }
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
    
    public function getCustomerDto(){
        return $this->customerDto;   
    }
}
?>