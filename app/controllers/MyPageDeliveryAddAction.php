<?php
namespace Controllers;
use \Models\DeliveryDao;
use \Models\CommonValidator;

class MyPageDeliveryAddAction {
    
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
        
        if($cmd == "do_logout" ){
            unset($_SESSION['customer_id']);
        }
        

        if(!isset($_SESSION["customer_id"])){
            header("Location:/html/login.php");   
            exit();
        }else{
            $customerId = $_SESSION['customer_id'];   
        }
        
        /*====================================================================
            order_delivery_list.phpからきた場合
        =====================================================================*/

        if($cmd == "from_order"){
            $_SESSION['from_order_flag'] = "is";   
        }
        
        /*====================================================================
            「配送先の保存ボタン」がおされたときの処理
        =====================================================================*/

        if($cmd == 'add'){
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
            
            $_SESSION['del_add'] = array(
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

            $validator = new CommonValidator();

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
                $_SESSION['add_data'] = "clear"; 
                header('Location:/html/mypage/mypage_delivery_add_complete.php');
                exit();
            }
        }
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
