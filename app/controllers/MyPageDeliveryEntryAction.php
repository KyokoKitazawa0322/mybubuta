<?php
namespace Controllers;

use \Models\DeliveryDao;
use \Models\OriginalException;
use \Config\Config;
use \Models\CommonValidator;

class MyPageDeliveryEntryAction {
    
    private $customerDto; 
    private $deliveryDto;
    
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
        
    public function execute(){
        
        $cmd = filter_input(INPUT_POST, 'cmd');
        $delId = filter_input(INPUT_POST, 'del_id');
        
        if($cmd == "do_logout" ){
            unset($_SESSION['customer_id']);
        }
        
        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
        
        /*=============================================================
    　　　　mypage_delivery.phpで「配送先の編集」ボタンがおされたときの処理
        =============================================================*/
        
        if($cmd == "del_update"){
            unset($_SESSION['del_update']);
            $_SESSION['del_id'] = $delId;
        }
        
        /*==============================================================
        　order_delivery_list.phpで「配送先の編集」ボタンがおされたときの処理
        ==============================================================*/
        elseif($cmd == "from_order"){
            $_SESSION['from_order_flag'] = "is";  
            unset($_SESSION['del_update']);
            $_SESSION['del_id'] = $delId;
        }
        
        /*==============================================================
        　それ以外の訪問はlogin.phpへリダイレクト
        ==============================================================*/
        else{
            header('Location:/html/login.php');   
        }
        
        /*=============================================================*/
        
        $deliveryId = $_SESSION['del_id'];
    
        $deliveryDao = new DeliveryDao();
        $validator = new CommonValidator();
        
        try{
            $this->deliveryDto = $deliveryDao->getDeliveryInfoById($customerId, $deliveryId);
            
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
        　「配送先の保存」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == 'register_del'){

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

            $_SESSION['del_update'] = array(
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
                 'tel' => $tel
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

            $key="電話番号";
            $this->telError = $validator->telValidation($key, $tel);

            if($validator->getResult()) {
                /*- バリデーションを全て通過したときの処理 -*/
                $_SESSION['update_data'] = "clear"; 
                header('Location:/html/mypage/mypage_delivery_complete.php');
                exit();
            }
        }
    }

    public function getCustomerDto(){
        return $this->customerDto;   
    }

    public function getDeliveryDto(){
        return $this->deliveryDto;   
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
}
?>