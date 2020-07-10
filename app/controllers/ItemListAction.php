<?php
namespace Controllers;

use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class ItemListAction {
    private $items;
    private $topItems;
    
    public function execute() {
        
        $cmd = filter_input(INPUT_GET, 'cmd');
        $keyWord = filter_input(INPUT_GET, 'keyword');
        $minPrice = filter_input(INPUT_GET, 'min_price');
        $maxPrice = filter_input(INPUT_GET, 'max_price');
        $sortKey = filter_input(INPUT_GET, 'sortkey');

        /*- 検索条件をリセット -*/
        if($cmd == "do_search" || $cmd == "item_list") {
            $_SESSION['search'] = array();
        }

        /*- カテゴリのGET値があるか確認 -*/
        $isCategory  = FALSE;
        foreach(config::CATEGORY as $key=>$value){
            if(isset($_GET[$key])){
                $isCategory = TRUE;
            }
        }
        /*- カテゴリのGET値が1つ以上ある場合 -*/
        if($isCategory){
            $categories = [];
            foreach(Config::CATEGORY as $key=>$value){ 
                if(isset($_GET[$key])){
                    $categories[] = $key;
                    $_SESSION['search']['category'][$key] = $value;
                } 
            }
        /*- カテゴリのGET値はないがセッション値がある場合 -*/
        }elseif(isset($_SESSION['search']['category'])){
            $categories = [];
            foreach(Config::CATEGORY as $key=>$value){
                if(isset($_SESSION['search']['category'][$key])){
                    $categories[] = $key;
                }
            }
        /*- カテゴリのGET値もセッション値もない場合 -*/
        }else{
            $categories = "";   
        }

        if($keyWord){
            $_SESSION['search']['keyword'] = $keyWord;
        }elseif(isset($_SESSION['search']['keyword'])){
            $keyWord = $_SESSION['search']['keyword'];
        }
        
        if($minPrice){
            $_SESSION['search']['min_price'] = $minPrice;
        }elseif(isset($_SESSION['search']['min_price'])){   
            $minPrice = $_SESSION['search']['min_price'];
        }
        
        if($maxPrice){
            $_SESSION['search']['max_price'] = $maxPrice;
        }elseif(isset($_SESSION['search']['max_price'])){   
            $maxPrice = $_SESSION['search']['max_price'];
        }
        
        if(!$sortKey){
            $sortKey = "04";/*- "ORDER BY item_insert_date desc" -*/
        }
        
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $itemsDao = new ItemsDao($pdo);
            $this->items = $itemsDao->findItems($categories, $keyWord, $minPrice, $maxPrice, $sortKey);
            $this->topItems = $itemsDao->getItemsInfoRankByWeek();
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
            
        } catch(MyPDOException $e){
            $e->handler($e);
        }
    }
    
    public function getItems(){
        return $this->items;   
    }
    
    public function getTopItems(){
        return $this->topItems;   
    }
    
    public function checkRequest(){
        $cmd = filter_input(INPUT_GET, 'cmd');
        $sortkey = filter_input(INPUT_GET, 'sortkey');
        if($cmd !== "do_search" && !$sortkey){
            return true;
       }
    }

    public function checkSortkey($value){
        $sortkey = filter_input(INPUT_GET, 'sortkey');
        if($sortkey==$value){
            return true;
        }
    }
    
    public function checkSelectedSortkey($value){
        $sortkey = filter_input(INPUT_GET, 'sortkey');
        if($sortkey==$value){
            echo "selected";   
        }
        if($sortkey=="03"){
            if(!$sortkey){
                echo "selected";   
            }
        }
    }
}
?>    

   