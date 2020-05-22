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

        if(isset($_GET['cmd'])){
            if($_GET['cmd']=="do_search" || $_GET['cmd']=="item_list") {
                $_SESSION['search'] = array();
            }
        }

        if(isset($_GET['coat']) || isset($_GET['dress']) || isset($_GET['skirt']) || isset($_GET['tops']) || isset($_GET['pants']) || isset($_GET['bag'])) {
            //SQL文にカテゴリー追加
            $category = [];
            foreach(Config::CATEGORY as $key=>$value){
                if(isset($_GET[$key])){
                    $category[] = $key;
                    $_SESSION['search'][$key] = $_GET[$key];
                } 
            }
            $dao->setCategoryIntoSql($category);
        }

        if(!empty($_GET['keyword'])){
            $keyWord = $_GET['keyword'];
            //SQL文に検索キーワード追加
            $dao->setKeywordIntoSql($keyWord);
            $_SESSION['search']['keyword'] = $keyWord;
        }
        
        if(isset($_GET['min_price'])){
            $minPrice = $_GET['min_price'];   
        }
        if(isset($_GET['max_price'])){
            $maxPrice = $_GET['max_price'];   
        }

        if(!empty($minPrice) && empty($maxPrice)){
            //SQL文に下限価格追加
            $dao->fsetMinPriceIntoSql($minPrice);
            $_SESSION['search']['min_price'] = $minPrice;
        }

        if(empty($minPrice) && !empty($maxPrice)){
            //SQL文に上限価格追加
            $dao->setMaxPriceIntoSql($maxPrice);
            $_SESSION['search']['max_price'] = $maxPrice;
        }

        if(!empty($minPrice) && !empty($maxPrice)){
            //SQL文に下限、上限価格追加
            $dao->setPriceIntoSql($minPrice, $maxPrice);
            $_SESSION['search']['min_price'] = $minPrice;
            $_SESSION['search']['max_price'] = $maxPrice;            
        }

        if(isset($_GET['cmd']) && $_GET['cmd'] == "do_search" ){
            //SQL文をセッションに保存
            $_SESSION['search']['sql'] = $dao->getSql();
        }

        if(isset($_GET['sortkey'])){
            $sortkey = $_GET['sortkey'];
            if(!isset($_SESSION['search']['sql'])){
                $dao->setOnlySortSqlInto($sortkey);
            }else{
                $searchsql = $_SESSION['search']['sql'];
                $dao->setSortIntoSql($sortkey, $searchsql);   
            }
        }else{
            $dao->setOrderDefaultIntoSql();   
        }

        try{
            //SQL実行
            $this->items = $dao->findItemsExecute();
            $this->topItems = $dao->selectItemsRank();
        }catch(\PDOException $e){
            die('SQLエラー :'.$e->getMessage());
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

   