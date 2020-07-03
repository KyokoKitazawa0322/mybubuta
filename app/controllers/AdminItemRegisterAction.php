<?php
namespace Controllers;

use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\UploadFileDao;

use \Models\CommonValidator;
use \Config\Config;

use \Models\InvalidParamException;
use \Models\NoRecordException;
use \Models\DBParamException;
use \Models\MyPDOException;
use \Models\MyS3Exception;
use \Models\UploadException;
    
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
        
        if($cmd == "admin_item_register"){
            
            $validator = new CommonValidator();
            
            $itemCode = filter_input(INPUT_POST, 'item_code');
            $itemName = filter_input(INPUT_POST, 'item_name'); 
            $itemPrice = filter_input(INPUT_POST, 'item_price', FILTER_SANITIZE_NUMBER_INT);
            $itemCategory = filter_input(INPUT_POST, 'item_category');  
            $itemStock = filter_input(INPUT_POST, 'item_stock', FILTER_SANITIZE_NUMBER_INT);
            $itemStatus = filter_input(INPUT_POST, 'item_status');
            $itemDetail = filter_input(INPUT_POST, 'item_detail'); 
            
            $_SESSION['admin_register'] = array (
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
            
            $key = "商品コード";
            $this->itemCodeError = $validator->requireCheck($key, $itemCode);    
            
            $key = "商品名";
            $this->itemNameError = $validator->requireCheck($key, $itemName);    

            $key = "商品価格";
            $result = $validator->priceValidation($key, $itemPrice);
            $this->itemPriceError = $result;

            $key = "商品カテゴリー";
            $this->itemCategoryError = $validator->requireCheck($key, $itemCategory);    
            
            $key = "在庫";
            $this->itemStockError = $validator->priceValidation($key, $itemStock);
            
            $key = "ステータス";
            $this->itemStatusError = $validator->requireCheck($key, $itemStatus);
            
            if($itemStock == 0 && $itemStatus == "1"){
                $this->itemStatusError = "在庫が0のため「販売中」は選択できません";
            }
            
            $key = "商品詳細";
            $this->itemDetailError = $validator->requireCheckForTextarea($key, $itemDetail);
          
            if(!$this->getItemStatusError()){
        
                try{
                    //DBに格納する値を代入
                    if($itemStatus == "1"){
                        $itemStatus = "1";//販売中     
                    }elseif($itemStatus == "2"){
                        $itemStatus = "2";//入荷待ち  
                    }elseif($itemStatus == "3"){
                        $itemStatus = "6";//販売前待機中
                    }else{
                        throw new InvalidParamException("invalid param in input-radio:{$itemStatus}");  
                    }
                } catch(InvalidParamException $e){
                    $e->handler($e);
                }
            }

            if($validator->getResult() && !$this->getItemImageError()) {
                    /*- バリデーション通過した時の処理 -*/
                try{
                    $result = $this->uploadImage($itemImageName, $itemOriginImageName);
                    $itemImagePath = $result;

                    $itemsDao = new ItemsDao();
                    $itemDto = $itemsDao->insertItemInfo($itemCode, $itemName, $itemPrice, $itemCategory, $itemImageName, $itemImagePath, $itemDetail, $itemStock, $itemStatus);
                    
                    unset($_SESSION['admin_register']);
                    
                } catch(MyS3Exception $e){
                    $e->handler($e);

                } catch(MyPDOException $e){
                    $e->handler($e);
                }
            }
        }
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
    
    public function checkSelectedStatus($value){
        if(isset($_SESSION['admin_register']['status']) && $_SESSION['admin_register']['status']==$value){ 
            echo "selected";
        }
    }
    
    public function echoValue($value){
        if(isset($_SESSION['admin_register'][$value])){
            echo $_SESSION['admin_register'][$value];
        }
    }
    
    public function checkSelectedCategory($value){
        if(isset($_SESSION['admin_register']['category']) && $_SESSION['admin_register']['category']==$value){ 
            echo "selected";
        }
    }
}
?>