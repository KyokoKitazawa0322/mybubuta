<?php
namespace Controllers;

use \Models\DeliveryDao;
use \Models\Model;

use \Models\CommonValidator;
use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class MyPageDeliveryAddAction extends \Controllers\CommonMyPageAction{
    
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
    private $buildingNameError = false;
    private $telError = false;   
        
    public function execute(){
        
        $cmd = Config::getPOST("cmd");
        
        $this->checkLogoutRequest($cmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
        
        /*====================================================================
            order_delivery_list.phpからきた場合
        =====================================================================*/

        if($cmd == "from_order"){
            $_SESSION['track_for_order'] = "order_delivery_list";   
        }
        
        /*====================================================================
            「配送先の保存ボタン」がおされたときの処理
        =====================================================================*/

        if($cmd == 'add'){
            
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

            $key="電話番号";
            $this->telError = $validator->telValidation($key, $tel);

            if($validator->getResult()) {
                /*- バリデーションを全て通過したときの処理 -*/
                $_SESSION['del_add']['add_data'] = "complete"; 
                header('Location:/html/mypage/delivery/mypage_delivery_add_confirm.php');
                exit();
            }else{
                $_SESSION['del_add']['add_data'] = "incomplete"; 
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
    
    public function getBuildingNameError(){
        return $this->buildingNameError;   
    }
    
    public function getTelError(){
        return $this->telError;   
    }
    
    public function checkSelectedPrefecture($value){
        if(isset($_SESSION['del_add']['prefecture']) && $_SESSION['del_add']['prefecture']==$value){ 
            return true;
        }
    }
    
    public function echoValue($value){
        if(isset($_SESSION['del_add'][$value])){
            echo $_SESSION['del_add'][$value];
        }
    }
}
?>
