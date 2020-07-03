<?php
namespace Controllers;

use \Models\ItemsDao;
use \Models\ItemsDto;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\InvalidParamException;
use \Models\MyPDOException;

use \Config\Config;
    
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
        
        $content = filter_input(INPUT_POST, 'content');
        $itemCode = filter_input(INPUT_POST, 'item_code');
        $itemsDao = new ItemsDao();
        
        if($cmd == "delete"){
            $itemsDao->deleteItem($itemCode);    
        }
         
        if($cmd == "search"){
            /*- 検索条件をリセットし以降の処理で更新 -*/
            $_SESSION['admin_search'] = array();
        }
        
        if($cmd == "reset"){
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
        
        //DBに格納する値を代入(現行はvalueと一致してるが念のため)
        if($status){
            try{
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
            }catch(InvalidParamException $e){
                $e->handler($e);
            }
        }else{
            $itemStatus = "";   
        }
            
        if($itemCode){
            $_SESSION['admin_search']['item_code'] = $itemCode;
        }elseif(isset($_SESSION['admin_search']['item_code'])){
            $itemCode = $_SESSION['admin_search']['item_code'];
        }

        if($keyword){
            $_SESSION['admin_search']['keyword'] = $keyword;
        }elseif(isset($_SESSION['admin_search']['keyword'])){
            $keyword = $_SESSION['admin_search']['keyword'];
        }

        if($category){
            $_SESSION['admin_search']['category'] = $category;
        }elseif(isset($_SESSION['admin_search']['category'])){
            $category = $_SESSION['admin_search']['category'];
        }

        if($status){
            $_SESSION['admin_search']['status'] = $status;
        }elseif(isset($_SESSION['admin_search']['status'])){
            $status = $_SESSION['admin_search']['status'];
        }

        if($minPrice){
            $_SESSION['admin_search']['min_price'] = $minPrice;
        }elseif(isset($_SESSION['admin_search']['min_price'])){   
            $minPrice = $_SESSION['admin_search']['min_price'];
        }

        if($maxPrice){
            $_SESSION['admin_search']['max_price'] = $maxPrice;
        }elseif(isset($_SESSION['admin_search']['max_price'])){   
            $maxPrice = $_SESSION['admin_search']['max_price'];
        }

        if($content){
            //findItemsForAdminメソッドで使用する値を代入(現行はvalueと一致してるが念のため)
            //下記文字列を引数として、メソッド内で更に条件分岐で精査
            try{
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

    public function getItems(){
        return $this->itemsDto;   
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