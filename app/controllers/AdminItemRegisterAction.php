<?php
namespace Controllers;

use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\UploadFileDao;
use \Models\Model;

use \Models\CommonValidator;
use \Models\CsrfValidator;
use \Config\Config;

use \Models\InvalidParamException;
use \Models\NoRecordException;
use \Models\DBParamException;
use \Models\MyPDOException;
use \Models\MyS3Exception;
use \Models\UploadException;
use \Models\DBConnectionException;
    
class AdminItemRegisterAction extends UploadFileDao{
    
    private $item;
    
    private $itemNameError;
    private $itemCodeError;
    private $itemPriceError;
    private $itemCategoryError;
    private $itemStockError;
    private $itemStatusError;
    private $itemDetailError;
    private $itemImageError;
    
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
    
        $sessionKey = "admin_item_register-".rand(5,10);
        $this->sessionKey = $sessionKey;
        
        $password = Config::getPOST("password");
        
        if($cmd == "admin_item_register"){
            $token = Config::getPOST( "token");
            try{
                CsrfValidator::validate($token);
            }catch(InvalidParamException $e){
                $e->handler($e);   
            }
            
            $itemCode = Config::getPOST('item_code');
            $itemName = Config::getPOST('item_name'); 
            $itemPrice = Config::getPOSTWithFilter('item_price', FILTER_SANITIZE_NUMBER_INT);
            $itemCategory = Config::getPOST('item_category');  
            $itemStock = Config::getPOSTWithFilter('item_stock', FILTER_SANITIZE_NUMBER_INT);
            $itemStatus = Config::getPOST('item_status');
            $itemDetail = Config::getPOST('item_detail'); 
            
            $_SESSION[$sessionKey] = array (
                'item_name' => $itemName, 
                'item_code' => $itemCode,  
                'item_price' => $itemPrice,  
                'item_stock' => $itemStock,
                'item_category' => $itemCategory,
                'item_status' => $itemStatus,
                'item_detail' => $itemDetail  
            );
            
            try{
                if(!isset($_FILES['image']['error']) || !is_int($_FILES['image']['error'])) {
                    $file = print_r($_FILES, true);
                    throw new InvalidParamException("invalid param for upload image:({$file})");

                }elseif($_FILES['image']['error'] == UPLOAD_ERR_OK){
                    $itemImageName = $_FILES['image']['name'];
                    $itemOriginImageName = $_FILES['image']['tmp_name'];              
                }else{
                    throw new UploadException($_FILES['image']['error']);   
                }

            }catch(InvalidParamException $e){
                $e->handler($e);
                
            }catch(UploadException $e){
                $e->handler_light($e);
                $this->itemImageError = $e->getUserMessage();
            }
            
            $validator = new CommonValidator();
            
            $key = "商品名";
            $limit = 30;
            $this->itemNameError = $validator->fullWidthValidation($key, $itemName, $limit); 
            
            try{
                $model = Model::getInstance();
                $pdo = $model->getPdo();
                $itemsDao = new ItemsDao($pdo);
                $itemsDto = $itemsDao->getItemByItemCode($itemCode);
                
                if($itemsDto){
                    $this->itemCodeError = "商品コードはすでに使用されてます。";   
                }else{
                    $key = "商品コード";
                    $this->itemCodeError = $validator->itemCodeValidation($key, $itemCode);   
                }
            }catch(DBConnectionException $e){
                $e->handler($e);   
                
            } catch(DBParamException $e){
                //
            }
            
            $key = "商品価格";
            $limit = 999999;
            $result = $validator->priceValidation($key, $itemPrice, $limit);
            $this->itemPriceError = $result;

            $key = "商品カテゴリー";
            $this->itemCategoryError = $validator->requireCheck($key, $itemCategory);    
            
            $key = "在庫";
            $limit = 999999;
            $this->itemStockError = $validator->stockValidation($key, $itemStock, $limit);
            
            $key = "ステータス";
            $this->itemStatusError = $validator->requireCheck($key, $itemStatus);
            
            if($itemStock == 0 && $itemStatus == "1"){
                $this->itemStatusError = "在庫が0のため「販売中」は選択できません";
            }
            
            $key = "商品詳細";
            $limit = 500;
            $this->itemDetailError = $validator->textAreaValidation($key, $itemDetail, $limit);
          
            if(!$this->getItemStatusError()){
        
                try{
                    $itemStatus = $this->checkItemStatus($itemStatus);
                } catch(InvalidParamException $e){
                    $e->handler($e);
                }
            }

            if($validator->getResult() && !$this->getItemImageError()) {
                    /*- バリデーション通過した時の処理 -*/ 
                $confirmPassword = getenv('CONFIRM_PASSWORD');
                
                if($password !== $confirmPassword){
                    try{
                        $result = $this->uploadImage($itemImageName, $itemOriginImageName);
                        $itemImagePath = $result;

                        $model = Model::getInstance();
                        $pdo = $model->getPdo();
                        $itemsDao = new ItemsDao($pdo);
                        $itemDto = $itemsDao->insertItemInfo($itemCode, $itemName, $itemPrice, $itemCategory, $itemImageName, $itemImagePath, $itemDetail, $itemStock, $itemStatus);

                        unset($_SESSION[$sessionKey]);

                    }catch(DBConnectionException $e){
                        $e->handler($e);   

                    } catch(MyS3Exception $e){
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
    
    /**
    * 下記文字列を引数として、メソッド内で更に条件分岐で精査
    * itemsDAOのメソッドに引数として渡す値を代入
    * throw InvalidParamException
    * return String $itemStatus
    **/
    public function checkItemStatus($status){
        switch($status){
            case "1":
                $itemStatus = "1";//販売中   
                break;
            case "2":
                $itemStatus = "2";//入荷待ち
                break;
            case "3":
                $itemStatus = "6";//販売前待機中
                break;
            default:
                throw new InvalidParamException('invalid param in $_POST["item_status"]:'.$status);           
        }
        return $itemStatus;
    }
    
    public function getItem(){
        return $this->itemDto;   
    }
    
    public function getItemNameError(){
        return $this->itemNameError;   
    }
    
    public function getItemCodeError(){
        return $this->itemCodeError;   
    }
    
    public function getItemPriceError(){
        return $this->itemPriceError;   
    }
 
    public function getItemCategoryError(){
        return $this->itemCategoryError;   
    }
    
    public function getItemStockError(){
        return $this->itemStockError;   
    }
    
    public function getItemStatusError(){
        return $this->itemStatusError;   
    }
    
    public function getItemDetailError(){
        return $this->itemDetailError;   
    }
    
    public function getItemImageError(){
        return $this->itemImageError;   
    }
    
    public function getErrorMessage(){
        return $this->errorMessage;   
    }
    
    public function checkSelectedStatus($value){
        $sessionKey = $this->sessionKey;
        if(isset($_SESSION[$sessionKey]['item_status']) && $_SESSION[$sessionKey]['item_status']==$value){ 
            echo "checked";
        }
    }
    
    public function echoValue($value){
        $sessionKey = $this->sessionKey;
        if(isset($_SESSION[$sessionKey][$value])){
            echo $_SESSION[$sessionKey][$value];
        }
    }
    
    public function checkSelectedCategory($value){
        $sessionKey = $this->sessionKey;
        if(isset($_SESSION[$sessionKey]['item_category']) && $_SESSION[$sessionKey]['item_category']==$value){ 
            echo "checked";
        }
    }
}
?>