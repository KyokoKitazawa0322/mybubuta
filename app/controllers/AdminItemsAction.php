<?php
namespace Controllers;

use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;
use \Models\DBConnectionException;
    
class AdminItemsAction{
    
    private $itemsDto;
        
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
        
        unset($_SESSION['update_item_code']);
        $content = filter_input(INPUT_POST, 'content');
        $itemCode = filter_input(INPUT_POST, 'item_code');
        
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $itemsDao = new ItemsDao($pdo);
    
        }catch(DBConnectionException $e){
            $e->handler($e);   
        }
        
        if($cmd == "delete"){
            try{
                $itemsDao->deleteItem($itemCode);
            
            } catch(MyPDOException $e){
                $e->handler($e);

            }catch(DBParamException $e){
                $e->handler($e);
            }
        }
         
        if($cmd == "search" || $cmd == "reset"){
            /*- 検索条件をリセット -*/
            $_SESSION['admin_search'] = array();
        }
        
        $itemCode = filter_input(INPUT_POST, 'search_item_code');
        $keyword = filter_input(INPUT_POST, 'search_keyword');
        $category = filter_input(INPUT_POST, 'search_category');
        $status = filter_input(INPUT_POST, 'search_status');
        $minPrice = filter_input(INPUT_POST, 'search_minprice');
        $maxPrice = filter_input(INPUT_POST, 'search_maxprice');
        $content = filter_input(INPUT_POST, 'content');
        
        if($status){
            try{
                $itemStatus = $this->checkItemsStatus($status);
    
            }catch(InvalidParamException $e){
                $e->handler($e);
            }
        }else{
            $itemStatus = "";   
        }
            
        $itemCode = $this->checkIssetValue($itemCode, "item_code");
        $keyword = $this->checkIssetValue($keyword, "keyword");
        $category = $this->checkIssetValue($category, "category");
        $status = $this->checkIssetValue($status, "status");
        $minPrice = $this->checkIssetValue($minPrice, "min_price");
        $maxPrice = $this->checkIssetValue($minPrice, "max_price");

        if($content){
            try{
                $sortkey = $this->checkSortContent($content);   
                
            }catch(InvalidParamException $e){
                $e->handler($e);
            }
        }else{
            $sortkey = "";   
        }


        try{
            $this->itemsDto = $itemsDao->findItemsForAdmin($category, $keyword, $minPrice, $maxPrice, $sortkey, $itemCode, $itemStatus);

        } catch(MyPDOException $e){
            $e->handler($e);
        }
    }

    /*---------------------------------------*/
    public function getItems(){
        return $this->itemsDto;   
    }

    /**
    * 下記文字列を引数として、メソッド内で更に条件分岐で精査
    * itemsDAOのメソッドに引数として渡す値を代入(現行はvalueと一致してるが念のため)
    * throw InvalidParamException
    * return String $itemStatus
    **/
    public function checkItemsStatus($status){
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
                throw new InvalidParamException("invalid param in name=search_status:{$status}");           
        }
        return $itemStatus;
    }
    
    /**
    * 下記文字列を引数として、メソッド内で更に条件分岐で精査
    * itemsDAOのメソッドに引数として渡す値を代入(現行はvalueと一致してるが念のため)
    * throw InvalidParamException
    * return String $sortkey
    **/
    public function checkSortContent($content){   
        switch($content){
            case "item_price_desc":
                $sortkey = "item_price_desc";
                break;
            case "item_stock_desc":
                $sortkey = "item_stock_desc";
                break;
            case "item_sales_desc":
                $sortkey = "item_sales_desc";
                break;
            case "item_insert_date_desc":
                $sortkey = "item_insert_date_desc";
                break;
            case "item_updated_date_desc":
                $sortkey = "item_updated_date_desc";
                break;
            case "item_price_asc":
                $sortkey = "item_price_asc";
                break;
            case "item_stock_asc":
                $sortkey = "item_stock_asc";
                break;
            case "item_sales_asc":
                $sortkey = "item_sales_asc";
                break;
            case "item_insert_date_asc":
                $sortkey = "item_insert_date_asc";
                break;
            case "item_updated_date_asc":
                $sortkey = "item_updated_date_asc";
                break;
            default:
                throw new InvalidParamException("invalid param in id=content:{$content}");           
        }
        return $sortkey;
    }
    
    public function checkIssetValue($value, $key){
        if($value){
            $_SESSION['admin_search'][$key] = $value;
        }elseif(isset($_SESSION['admin_search'][$key])){
            $value = $_SESSION['admin_search'][$key];
        }
        return $value;
    }

    public function checkSelectedStatus($value){
        if(isset($_SESSION['admin_search']['status']) && $_SESSION['admin_search']['status']==$value){ 
            echo "selected";
        }
    }
    
    public function echoValue($value){
        if(isset($_SESSION['admin_search'][$value])){
            echo $_SESSION['admin_search'][$value];
        }
    }
    
    public function checkSelectedCategory($value){
        if(isset($_SESSION['admin_search']['category']) && $_SESSION['admin_search']['category']==$value){ 
            echo "selected";
        }
    }
}
?>