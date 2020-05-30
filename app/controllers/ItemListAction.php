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
        
        //検索条件をリセット
        if(isset($_GET['cmd'])){
            if($_GET['cmd']=="do_search" || $_GET['cmd']=="item_list") {
                $_SESSION['search'] = array();
            }
        }

        if(isset($_GET['coat']) || isset($_GET['dress']) || isset($_GET['skirt']) || isset($_GET['tops']) || isset($_GET['pants']) || isset($_GET['bag'])) {
            $categories = [];
            foreach(Config::CATEGORY as $key=>$value){ 
                if(isset($_GET[$key])){
                    $categories[] = $key;
                    $_SESSION['search']['category'][$key] = $value;
                } 
            }
        }elseif(isset($_SESSION['search']['category'])){
            $categories = [];
            foreach(Config::CATEGORY as $key=>$value){
                if(isset($_SESSION['search']['category'][$key])){
                    $categories[] = $key;
                }
            }
        }else{
            $categories = "";   
        }

        if(!empty($_GET['keyword'])){
            $keyWord = $_GET['keyword'];
            $_SESSION['search']['keyword'] = $keyWord;
        }elseif(isset($_SESSION['search']['keyword'])){
            $keyWord = $_SESSION['search']['keyword'];
        }else{
            $keyWord = "";   
        }
        
        if(isset($_GET['min_price'])){
            $minPrice = $_GET['min_price'];
            $_SESSION['search']['min_price'] = $minPrice;
        }elseif(isset($_SESSION['search']['min_price'])){   
            $minPrice = $_SESSION['search']['min_price'];
        }else{
            $minPrice = "";   
        }
        
        if(isset($_GET['max_price'])){
            $maxPrice = $_GET['max_price'];  
            $_SESSION['search']['max_price'] = $maxPrice;
        }elseif(isset($_SESSION['search']['max_price'])){   
            $maxPrice = $_SESSION['search']['max_price'];
        }else{
            $maxPrice = "";   
        }
        
        if(isset($_GET['sortkey'])){
            $sortKey = $_GET['sortkey'];
        }else{
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

   