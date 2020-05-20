<?php

namespace Controllers;
use \Models\CustomerDao;
use \Models\CustomersDto;
use \Models\CommonValidator;

class RegisterAction{
    
    private $lastNameError = false;
    private $firstNameError = false;
    private $rubyLastNameError = false;
    private $rubyFirstNameError = false;
    private $address01Error = false;
    private $address02Error = false;
    private $address03Error = false;
    private $address04Error = false;
    private $address05Error = false;
    private $address06Error = false;
    private $telError = false;
    private $mailError = false;
    private $passwordError = false;
    private $passwordConfirmError = false;
        
        
    public function execute(){
        
        try{
            $customerDao = new CustomerDao();
            $validator = new CommonValidator();

            if(isset($_POST['cmd']) && $_POST['cmd']=="confirm"){

                $lastName = $_POST['last_name'];
                $firstName = $_POST['first_name'];
                $rubyLastName = $_POST['ruby_last_name'];
                $rubyFirstName = $_POST['ruby_first_name'];
                $address01 = $_POST['address01'];
                $address02 = $_POST['address02'];
                $address03 = $_POST['address03'];
                $address04 = $_POST['address04'];
                $address05 = $_POST['address05'];
                $address06 = $_POST['address06'];
                $tel = $_POST['tel'];
                $mail = $_POST['mail'];
                $password = $_POST['password'];
                $passwordConfirm = $_POST['passwordConfirm'];

                $_SESSION['register'] = array(
                 'last_name' => $lastName,
                 'first_name' => $firstName,
                 'ruby_last_name' => $rubyLastName,
                 'ruby_first_name' => $rubyFirstName,
                 'address01' => $address01,
                 'address02' => $address02,
                 'address03' => $address03,
                 'address04' => $address04,
                 'address05' => $address05,
                 'address06' => $address06,
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
                $this->address01Error = $validator->firstZipCodeValidation($key, $address01);

                $key = "郵便番号(4ケタ)";
                $this->address02Error  = $validator->lastZipCodeValidation($key, $address02);

                $key="都道府県";
                $this->address03Error = $validator->requireCheck($key, $address03);

                $key="市区町村";
                $this->address04Error = $validator->requireCheck($key, $address04);

                $key="番地";
                $this->address05Error = $validator->requireCheck($key, $address05);

                $key="メールアドレス";
                $this->mailError = $validator->mailValidation($key, $mail);
                
                $key="電話番号";
                $this->telError = $validator->telValidation($key, $tel);

                $key="パスワード";
                $this->passwordError = $validator->passValidation($key, $password);

                $key="パスワード(再確認)";
                $this->passwordConfirmError = $validator->passConfirmValidation($key, $passwordConfirm, $password);

                if($validator->getResult()) {
                    $_SESSION['register']['input'] = "complete";
                    header('Location:/html/register/register_confirm.php');
                    exit();
                }
            }
        }catch(\PDOException $e){
            die('SQLエラー :'.$e->getMessage());
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
    
    public function getAddress01Error(){
        return $this->address01Error;   
    }
    
    public function getAddress02Error(){
        return $this->address02Error;   
    }
   
    public function getAddress03Error(){
        return $this->address03Error;   
    }
    
    public function getAddress04Error(){
        return $this->address04Error;   
    }
    
    public function getAddress05Error(){
        return $this->address05Error;   
    }
    
    public function getAddress06Error(){
        return $this->address06Error;   
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
}


?>    

   