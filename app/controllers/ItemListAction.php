<?php
namespace Controllers;
use \Models\ItemsDao;
use \Models\ItemsDto;
use \Config\Config;

class ItemListAction {
    private $items;
    private $topItems;
    
    public function execute() {
        
        $dao = new itemsDao();
        
        $cmd = filter_input(INPUT_GET, 'cmd');
        $keyWord = filter_input(INPUT_GET, 'keyword');
        $minPrice = filter_input(INPUT_GET, 'min_price');
        $maxPrice = filter_input(INPUT_GET, 'max_price');
        $sortKey = filter_input(INPUT_GET, 'sortkey');

        //検索条件をリセット
        if($cmd == "do_search" || $cmd == "item_list") {
            $_SESSION['search'] = array();
        }

        //カテゴリのGET値があるか確認
        $isCategory  = false;
        foreach(config::CATEGORY as $key=>$value){
            if(isset($_GET[$key])){
                $isCategory = true;
            }
        }
        //カテゴリのGET値が1つ以上ある場合
        if($isCategory){
            $categories = [];
            foreach(Config::CATEGORY as $key=>$value){ 
                if(isset($_GET[$key])){
                    $categories[] = $key;
                    $_SESSION['search']['category'][$key] = $value;
                } 
            }
        //カテゴリのGET値はないがセッション値がある場合
        }elseif(isset($_SESSION['search']['category'])){
            $categories = [];
            foreach(Config::CATEGORY as $key=>$value){
                if(isset($_SESSION['search']['category'][$key])){
                    $categories[] = $key;
                }
            }
        //カテゴリのGET値もセッション値もない場合
        }else{
            $categories = false;   
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
            $sortKey = "03";//"ORDER BY item_insert_date asc"
        }

        try{
            $this->items = $dao->searchItems($categories, $keyWord, $minPrice, $maxPrice, $sortKey);
            $this->topItems = $dao->selectItemsRank();
        } catch(\PDOException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            die('エラー:データベースの処理に失敗しました。');  
        }
    }
    
    public function getItems(){
        return $this->items;   
    }
    
    public function getTopItems(){
        return $this->topItems;   
    }
    
}
?>    

   