<?php
namespace Controllers;
use \Models\ItemsDao;
use \Models\ItemsDto;
use \Config\Config;

class ItemListAction {
    private $items;
    private $topItems;
    
    public function execute() {
        try{
            $dao = new itemsDao();

            if(isset($_GET["cmd"])){
                if($_GET['cmd']=="do_search" || $_GET['cmd']=="item_list") {
                    $_SESSION['search'] = array();
                }
            }

            if(isset($_GET['coat']) || isset($_GET['dress']) || isset($_GET['skirt']) || isset($_GET['tops']) || isset($_GET['pants']) || isset($_GET['bag'])) {
                //SQL文にカテゴリー追加(未実行)
                $dao->findItemsByCategory(Config::CATEGORY);
                foreach(Config::CATEGORY as $key=>$value){
                    if(isset($_GET[$key])){
                        $_SESSION['search'][$key] = $_GET[$key];   
                    } 
                }
            }

            if(!empty($_GET['item_name'])){
                //SQL文に検索キーワード追加（未実行）
                $dao->findItemsByName($_GET['item_name']);
                $_SESSION['search']['item_name'] = $_GET['item_name'];
            }

            if(!empty($_GET['min_price']) && empty($_GET['max_price'])){
                //SQL文に下限価格追加（未実行）
                $dao->findItemsByMinPrice($_GET['min_price']);
                $_SESSION['search']['min_price'] = $_GET['min_price'];
            }

            if(empty($_GET['min_price']) && !empty($_GET['max_price'])){
                //SQL文に上限価格追加（未実行）
                $dao->findItemsByMaxPrice($_GET['max_price']);
                $_SESSION['search']['max_price'] = $_GET['max_price'];
            }

            if(!empty($_GET['min_price']) && !empty($_GET['max_price'])){
                //SQL文に下限、上限価格追加（未実行）
                $dao->findItemsByPrice($_GET['min_price'], $_GET['max_price']);
                $_SESSION['search']['min_price'] = $_GET['min_price'];
                $_SESSION['search']['max_price'] = $_GET['max_price'];            
            }

            if(isset($_GET["cmd"]) && $_GET["cmd"] == "do_search" ){
                //SQL文をセッションに保存
                $_SESSION['search']['sql'] = $dao->getSql();
            }

            if(isset($_GET["sortkey"])){
                $sortkey = $_GET['sortkey'];
                if(!isset($_SESSION['search']['sql'])){
                    $dao->itemsSortOnly($sortkey);
                }else{
                    $searchsql = $_SESSION['search']['sql'];
                    $dao->itemsSort($sortkey, $searchsql);   
                }
            }else{
                $dao->itemsOrder();   
            }
            
            //SQL実行
            $this->items = $dao->findItemsExecute();
            $this->topItems = $dao->selectItemsRank();
            
        }catch(\PDOException $e){
            $e->getMessage();
            exit();
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

   