<?php
namespace Controllers;

use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\UploadFileDao;

use \Models\CommonValidator;
use \Models\CsrfValidator;
use \Config\Config;

use \Models\InvalidParamException;
use \Models\NoRecordException;
use \Models\DBParamException;
use \Models\MyPDOException;
use \Models\MyS3Exception;
use \Models\UploadException;

class AdminItemUpdateAction extends UploadFileDao{
    
    private $itemDto;
    
    private $itemNameError;
    private $itemCodeError;
    private $itemPriceError;
    private $itemStockError;
    private $itemStatusError;
    private $itemDetailError;
        
    public function execute(){
        
        /*====================================================================
      　  $_SESSION['admin_id']がなければadmin_login.phpへリダイレクト
        =====================================================================*/
        
        $cmd = filter_input(INPUT_POST, 'cmd');

        if($cmd == "admin_logout"){
            unset($_SESSION['admin_id']);    
        }
        
        if(!isset($_SESSION['admin_id'])){
            header("Location:/html/admin/admin_login.php");
            exit();
        }
        
        $itemCode = filter_input(INPUT_POST, 'item_code');
        /*====================================================================
          admin_items.phpで「更新」ボタンが押された時の処理
        =====================================================================*/
        if($cmd == "update"){

            $_SESSION['update_item_code'] = $itemCode;

        /*====================================================================
         「更新する」ボタンが押された時の処理
        =====================================================================*/
        }elseif($cmd == "update_confirm"){
            
            $token = filter_input(INPUT_POST, "token");
            try{
                CsrfValidator::validate($token);
            }catch(InvalidParamException $e){
                $e->handler($e);   
            }
            
            $validator = new CommonValidator();

            $itemName = filter_input(INPUT_POST, 'item_name'); 
            $updateItemCode = filter_input(INPUT_POST, 'update_item_code'); 
            $itemPrice = filter_input(INPUT_POST, 'item_price', FILTER_SANITIZE_NUMBER_INT);
            $itemStock = filter_input(INPUT_POST, 'item_stock', FILTER_SANITIZE_NUMBER_INT);
            $itemStatus = filter_input(INPUT_POST, 'item_status');
            $itemDetail = filter_input(INPUT_POST, 'item_detail'); 

            $_SESSION['admin_update'] = array (
                'item_name' => $itemName, 
                'item_code' => $updateItemCode,  
                'item_price' => $itemPrice,  
                'item_stock' => $itemStock,
                'item_status' => $itemStatus,
                'item_detail' => $itemDetail  
            );

            $key = "商品名";
            $this->itemNameError = $validator->requireCheck($key, $itemName);   
            
            $key = "商品コード";
            $this->itemCodeError = $validator->requireCheck($key, $updateItemCode);   

            $key = "商品価格";
            $result = $validator->priceValidation($key, $itemPrice);
            $this->itemPriceError = $result;

            $key = "在庫";
            $this->itemStockError = $validator->numberValidation($key, $itemStock);
                
            $key = "ステータス";
            $this->itemStatusError = $validator->requireCheck($key, $itemStatus);
                 
            if($itemStock == 0 && $itemStatus == "1"){
                $this->itemStatusError = "在庫が0のため「販売中」は選択できません";
            }
            
            $key = "商品詳細";
            $this->itemDetailError = $validator->requireCheckForTextarea($key, $itemDetail);
            $test = $this->itemDetailError;
            
            if(!$this->getItemStatusError()){
                try{
                    //DBに格納する値を代入(現行はradioのvalueと一致してるが念のため)
                    if($itemStatus == "1"){
                        $itemStatus = "1";//販売中     
                    }elseif($itemStatus == "2"){
                        $itemStatus = "2";//入荷待ち  
                    }elseif($itemStatus == "3"){
                        $itemStatus = "3";//販売終了
                    }elseif($itemStatus == "4"){
                        $itemStatus = "4";//一時掲載停止
                    }elseif($itemStatus == "5"){
                        $itemStatus = "5";//在庫切れ
                    }elseif($itemStatus == "6"){
                        $itemStatus = "6";//販売前待機中
                    }else{
                        throw new InvalidParamException("invalid param in input-radio:{$itemStatus}");  
                    }
                } catch(InvalidParamException $e){
                    $e->handler($e);
                }
            }

            if($validator->getResult() && !$this->getItemStatusError()){
                    /*- バリデーション通過した時の処理 -*/
                $itemsDao = new ItemsDao();
                try{
                    $itemsDao->updateItemInfo($itemName, $updateItemCode, $itemPrice, $itemStock, $itemStatus, $itemDetail, $itemCode);
                    
                    unset($_SESSION['admin_update']);
                    $_SESSION['update_item_code'] = $updateItemCode;
                        
                } catch(MyPDOException $e){
                    $e->handler($e);

                } catch(DBParamException $e){
                    $e->handler($e);
                }
            }
        /*====================================================================
         「画像を更新する」ボタンが押された時の処理
        =====================================================================*/
        }elseif($cmd == "upload_file"){

            $itemsDao = new ItemsDao();

            try{
                if(!isset($_FILES['image']['error']) || !is_int($_FILES['image']['error'])) {
                    
                    $file = print_r($_FILES, true);
                    throw new InvalidParamException("invalid param for upload image:({$file})");

                }elseif($_FILES['image']['error'] == UPLOAD_ERR_OK){
                    
                    $itemImageName = $_FILES['image']['name'];
                    $itemOriginImageName = $_FILES['image']['tmp_name'];    
                    
                    $result = $this->uploadImage($itemImageName, $itemOriginImageName);
                    $itemImagePath = $result;

                    $itemsDao->updateItemImage($itemImageName, $itemImagePath, $itemCode);  
                    
                }else{
                    throw new UploadException($_FILES['image']['error']);   
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

        if(!$cmd && !isset($_SESSION['update_item_code'])){
            header("Location:/html/admin/admin_login.php");
            exit();         
        }

        if($_SESSION['update_item_code']){
            $itemCode = $_SESSION['update_item_code'];   
            $itemsDao = new ItemsDao();
        }

        try{  
            $itemDto = $itemsDao->getItemByItemCode($itemCode);
            $this->itemDto = $itemDto;

        } catch(MyPDOException $e){
            $e->handler($e);

        } catch(DBParamException $e){
            $e->handler($e);
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
    
    public function checkSelectedStatus($value){
        if(isset($_SESSION['admin_update']['status']) && $_SESSION['admin_update']['status']==$value){ 
            echo "selected";
        }
    }
    
    public function echoValue($value, $value_2){
        if(isset($_SESSION['admin_update'][$value])){
            echo $_SESSION['admin_update'][$value];
        }else{
            echo $value_2;
        }
    }
}
?>