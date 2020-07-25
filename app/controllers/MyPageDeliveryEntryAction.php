<?php
namespace Controllers;

use \Models\DeliveryDao;
use \Models\DeliveryDTo;
use \Models\Model;

use \Config\Config;

use \Models\CommonValidator;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class MyPageDeliveryEntryAction extends \Controllers\CommonMyPageAction{
    
    private $customerDto; 
    private $deliveryDto;
    
    private $keyForUpdate;
    private $delId;
    
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
        
        $postCmd = Config::getPOST('cmd');
        $getCmd = Config::getGET('cmd');
        $delId = Config::getGET('del_id');
        
        $this->checkLogoutRequest($postCmd);
        $this->checkLogin();
        $customerId = $_SESSION['customer_id'];   
        
        $keyForUpdate = "del_update-".$delId;
        $this->keyForUpdate = $keyForUpdate;
        /*==============================================================
        　order_delivery_list.phpで「配送先の編集」ボタンがおされたときの処理
        ==============================================================*/
        if($getCmd == "from_order"){
            $_SESSION['track_for_order'] = "order_delivery_list";  
        }
    
        try{
            //GET通信でdelivery_idをもちまわる仕様のため(複数ウィンドウでの同時処理対応)、無い場合は例外処理
            if(!$delId){
                throw new InvalidParamException('Invalid param : $delId = nothing');
            }else{
                $this->delId = $delId;   
                
                
            }
        }catch(InvalidParamException $e){
            $e->handler($e);   
        }

        /*=============================================================*/      
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $deliveryDao = new DeliveryDao($pdo);
            $this->deliveryDto = $deliveryDao->getDeliveryInfoById($customerId, $delId);
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
            
        } catch(MyPDOException $e){
            $e->handler($e);
            
        }catch(DBParamException $e){
            $e->handler($e);
        }
        
        /*====================================================================
        　「配送先の保存」ボタンが押された時の処理
        =====================================================================*/
        if($postCmd == 'register_del'){

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
            
            $_SESSION[$keyForUpdate] = array(
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
                $_SESSION[$keyForUpdate]['delivery_entry_data'] = "complete"; 
                header("Location:/html/mypage/delivery/mypage_delivery_entry_confirm.php?del_id={$delId}");
                exit();
            }else{
                $_SESSION[$keyForUpdate]['delivery_entry_data'] = "incomplete"; 
            }
                        var_dump($_SESSION[$keyForUpdate]);
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
    
    public function getBuildingNameError(){
        return $this->buildingNameError;   
    }
    
    public function getTelError(){
        return $this->telError;   
    }
    
    public function getDelId(){
        return $this->delId;   
    }
    
    public function checkSelectedPrefecture($value, $customerData){
        $keyForUpdate = $this->keyForUpdate;
        if(isset($_SESSION[$keyForUpdate]['prefecture'])){
            if($_SESSION[$keyForUpdate]['prefecture']==$value){ 
                return true;
            }
        }elseif($customerData==$value){
            return true;    
        }
    }
    
    public function echoValue($value, $customerDate){
        $keyForUpdate = $this->keyForUpdate;
        if(isset($_SESSION[$keyForUpdate][$value])){
            echo $_SESSION[$keyForUpdate][$value];
        }else{
            echo $customerDate;
        }
    }
}
?>