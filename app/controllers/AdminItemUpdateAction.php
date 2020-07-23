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

class AdminItemUpdateAction extends UploadFileDao{
    
    private $itemDto;
    
    private $itemNameError;
    private $itemCodeError;
    private $itemPriceError;
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
        
        $postCmd = Config::getPOST("cmd");
        $getCmd = Config::getGET("cmd");
        
        if($postCmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }
        
        $password = Config::getPOST("password");
        $itemCode = Config::getGET("item_code");
        
        if(!$itemCode){
            header("Location:/html/admin/admin_login.php");
            exit();         
        }

        $sessionKey = "item_update-".$itemCode;
        $this->sessionKey = $sessionKey;
        /*====================================================================
         「更新する」ボタンが押された時の処理
        =====================================================================*/
        if($postCmd == "update_confirm"){
            $token = Config::getPOST("token");
            try{
                CsrfValidator::validate($token);
            }catch(InvalidParamException $e){
                $e->handler($e);   
            }

            $validator = new CommonValidator();

            $itemName = Config::getPOST("item_name");
            $itemPrice = Config::getPOSTWithFilter("item_price", FILTER_SANITIZE_NUMBER_INT);
            $itemStock = Config::getPOSTWithFilter("item_stock", FILTER_SANITIZE_NUMBER_INT);
            $itemStatus = Config::getPOST("item_status");
            $itemDetail = Config::getPOST("item_detail");
            
            $_SESSION[$sessionKey] = array (
                'item_name' => $itemName, 
                'item_price' => $itemPrice,  
                'item_stock' => $itemStock,
                'item_status' => $itemStatus,
                'item_detail' => $itemDetail  
            );

            $key = "商品名";
            $limit = 30;
            $this->itemNameError = $validator->fullWidthValidation($key, $itemName, $limit);   

            $key = "商品価格";
            $limit = 999999;
            $result = $validator->priceValidation($key, $itemPrice, $limit);
            $this->itemPriceError = $result;

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
            $test = $this->itemDetailError;

            if(!$this->getItemStatusError()){
                try{
                    $itemStatus = $this->checkItemStatus($itemStatus);
                } catch(InvalidParamException $e){
                    $e->handler($e);
                }
            }

            if($validator->getResult() && !$this->getItemStatusError()){
                /*- バリデーション通過した時の処理 -*/
                $confirmPassword = getenv('CONFIRM_PASSWORD');
                
                if($password == $confirmPassword){
                    try{
                        $model = Model::getInstance();
                        $pdo = $model->getPdo();
                        $itemsDao = new ItemsDao($pdo);
                        $itemsDao->updateItemInfo($itemName, $itemPrice, $itemStock, $itemStatus, $itemDetail, $itemCode);

                        unset($_SESSION[$sessionKey]);
                        
                    }catch(DBConnectionException $e){
                        $e->handler($e); 

                    } catch(MyPDOException $e){
                        $e->handler($e);

                    } catch(DBParamException $e){
                        $e->handler($e);
                    }
                }else{
                    $this->errorMessage = "デモ画面のため、実際の登録はできません。";
                }
            }
        }
        /*====================================================================
         「画像を更新する」ボタンが押された時の処理
        =====================================================================*/
        if($postCmd == "upload_file"){
            try{
                $model = Model::getInstance();
                $pdo = $model->getPdo();
                $itemsDao = new ItemsDao($pdo);

            }catch(DBConnectionException $e){
                $e->handler($e);   
            }

            try{
                $file = $this->checkFile();
                if($file){
                    if($password == $confirmPassword){

                        $itemImageName = $_FILES['image']['name'];
                        $itemOriginImageName = $_FILES['image']['tmp_name'];    

                        $result = $this->uploadImage($itemImageName, $itemOriginImageName);
                        $itemImagePath = $result;
                        $itemsDao->updateItemImage($itemImageName, $itemImagePath, $itemCode);  
                    }else{
                        $this->errorMessage = "デモ画面のため、実際の登録はできません。";
                    }
                }

            }catch(InvalidParamException $e){
                $e->handler($e);

            }catch(UploadException $e){
                $e->handler_light($e);
                $this->itemImageError = $e->getUserMessage();

            } catch(MyS3Exception $e){
                $e->handler($e);

            } catch(MyPDOException $e){
                $e->handler($e);

            } catch(DBParamException $e){
                $e->handler($e);
            }
        }

        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $itemsDao = new ItemsDao($pdo);
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
        }

        try{  
            $itemDto = $itemsDao->getItemByItemCode($itemCode);
            $this->itemDto = $itemDto;

        } catch(MyPDOException $e){
            $e->handler($e);

        } catch(DBParamException $e){
            $e->handler($e);
        }

        /*====================================================================
         「削除する」ボタンが押された時の処理
        =====================================================================*/
        if($postCmd == "delete_confirm"){
            $token = Config::getPOST("token");
            try{
                CsrfValidator::validate($token);
            }catch(InvalidParamException $e){
                $e->handler($e);   
            }
            /*- バリデーション通過した時の処理 -*/
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../../");
            $dotenv->load();
            $confirmPassword = $_ENV['CONFIRM_PASSWORD'];

            if($password == $confirmPassword){
                try{
                    $model = Model::getInstance();
                    $pdo = $model->getPdo();
                    $itemsDao = new ItemsDao($pdo);
                    $itemsDao->deleteItem($itemCode);

                }catch(DBConnectionException $e){
                    $e->handler($e); 

                } catch(MyPDOException $e){
                    $e->handler($e);

                } catch(DBParamException $e){
                    $e->handler($e);
                }

                header("Location:/html/admin/admin_items.php");
            
            }else{
                $this->errorMessage = "デモ画面のため、実際の削除はできません。";
            }
        }
    }
    /*---------------------------------------*/
    
    /**
    * 下記文字列を引数として、メソッド内で更に条件分岐で精査
    * itemsDAOのメソッドに引数として渡す値を代入(現行はvalueと一致してるが念のため)
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
                $itemStatus = "3";//販売終了   
                break;
            case "4":
                $itemStatus = "4";//一時掲載停止  
                break;
            case "5":
                $itemStatus = "5";//在庫切れ
                break;
            case "6":
                $itemStatus = "6";//販売前待機中  
                break;
            default:
                throw new InvalidParamException("invalid param in (post)item_status:{$status}");           
        }
        return $itemStatus;
    }
    
    /**
    * throw UploadException
    * throw InvalidParamException
    * return boolean true
    **/
    public function checkFile(){
        if(!isset($_FILES['image']['error']) || !is_int($_FILES['image']['error'])) {

            $file = print_r($_FILES, true);
            throw new InvalidParamException("invalid param for upload image:({$file})");

        }elseif($_FILES['image']['error'] == UPLOAD_ERR_OK){
            return true;
            
        }else{
            throw new UploadException($_FILES['image']['error']);   
        }
    }
    
    public function getItemDto(){
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
    
    public function getItemStockError(){
        return $this->itemStockError;   
    }
    
    public function getItemStatusError(){
        return $this->itemStatusError;   
    }
    
    public function getItemDetailError(){
        return $this->itemDetailError;   
    }
    
    public function getErrorMessage(){
        return $this->errorMessage;   
    }
    
    public function getItemImageError(){
        return $this->itemImageError;   
    }
    
    public function checkSelectedStatus($value, $itemData){
        $sessionKey = $this->sessionKey;
        if(isset($_SESSION[$sessionKey]['item_status'])){
            if($_SESSION[$sessionKey]['item_status']==$value){ 
                echo "checked";
            }
        }elseif($itemData == $value){
            echo "checked"; 
        }
    }
    
    public function echoValue($value, $value_2){
        $sessionKey = $this->sessionKey;
        if(isset($_SESSION[$sessionKey][$value])){
            echo $_SESSION[$sessionKey][$value];
        }else{
            echo $value_2;
        }
    }
}
?>