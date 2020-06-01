<?php
namespace Models;
use \Models\ItemsDto;
use \Models\OriginalException;

class ItemsDao extends \Models\Model { 

    public function __construct(){
        parent::__construct();
    }
    
    /**
     * 商品詳細を取得
     * $itemCodeをキーに商品情報を取得する。
     * @param string $itemCode 商品コード
     * @return ItemsDto
     * @throws PDOException
     * @throws OriginalException(取得失敗時:code444)
     */
    public function findItemByItemCode($itemCode){
        try{
            $dto = new ItemsDto();
            
            $sql = "SELECT * FROM items WHERE item_code = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemCode); 
            $stmt->execute();   
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;
            }else{
                throw new OriginalException('取得に失敗しました。',444);    
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
        
    public function setDto($res){
        
        $dto = new ItemsDto();
        
        $dto->setItemCode($res['item_code']);
        $dto->setItemName($res['item_name']);
        $dto->setItemPrice($res['item_price']);
        $dto->setTax($res['tax']);
        $dto->setItemCategory($res['item_category']);
        $dto->setItemImage($res['item_image']);
        $dto->setItemDetail($res['item_detail']); 
        
        return $dto;
    }
        
    /**
     * 商品検索
     * なければfalseを返す
     * @param string $categories カテゴリー：値があればSQL文を加えbindvalueにセット
     * @param string $keyWord キーワード：値があればSQL文を加えbindvalueにセット
     * @param int $minPrice 下限価格：値があればSQL文を加えbindvalueにセット
     * @param int $maxPrice 上限価格：値があればSQL文を加えbindvalueにセット
     * @param string $sortKey 並び替え条件：番号によって並び替え箇所のSQL文を振り分ける
     * @return ItemsDto[]
     * @throws PDOException
     */
    
    public function searchItems($categories, $keyWord, $minPrice, $maxPrice, $sortKey){
        try{
            $sql = "SELECT * FROM items WHERE item_del_flag = '0'";
            if($categories){
                $category = "";
                foreach($categories as $key){
                    $category = $category.':'.$key.',';
                }
                $category = preg_replace("/,$/", "", $category);
                $sql = $sql."AND item_category IN ($category) ";
            }
            if($keyWord){
                $sql = $sql."AND item_name LIKE :keyword ";
            }        
            if($minPrice){
                $sql = $sql."AND item_price+tax >= :minprice ";
            }
            if(!empty($maxPrice)){
                $sql = $sql."AND item_price+tax <= :maxprice ";
            }
            if($sortKey == "01"){
                $sql = $sql."ORDER BY item_price ASC";
            }elseif($sortKey == "02"){
                $sql = $sql."ORDER BY item_price DESC";
            }elseif($sortKey == "03"){
                $sql = $sql."ORDER BY item_sales DESC";
            }else{     
                $sql = $sql."ORDER BY item_insert_date ASC";
            }

            $stmt = $this->pdo->prepare($sql);

            if(!empty($categories)){
                foreach($categories as $category){
                    $stmt->bindvalue(":{$category}", $category, \PDO::PARAM_STR);
                    //IN (:skirt, :tops, :dress)-> 「''」がはいるとエスケープされるためバラバラに定義
                }
            }
            if($keyWord){
                $stmt->bindvalue(":keyword", '%'.$keyWord.'%', \PDO::PARAM_STR);
            }
            if($minPrice){
                $stmt->bindvalue(":minprice", $minPrice, \PDO::PARAM_INT);
            }
            if(!empty($maxPrice)){
                $stmt->bindvalue(":maxprice", $maxPrice, \PDO::PARAM_INT);
            }
            $stmt->execute();
            $res = $stmt->fetchAll();

            $items = [];
            if($res){
                foreach($res as $row) {
                    $dto = $this->setDto($row);
                    $items[] = $dto;
                }
                return $items;
            }else {
                return false;   
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 商品の人気ランキングを取得
     * 販売数量(oeder_detailテーブルのitem_codeの数をカウント)をキーに並び替え商品情報を5点まで取得する。
     * @return ItemsDto[]
     * @throws PDOException
     * @throws OriginalException(取得失敗時:code444)
     */
    public function selectItemsRank() {
        
        try{
            $sql = "SELECT * from items ORDER BY item_sales DESC LIMIT 5";
            $stmt = $this->pdo->query($sql); 
            $res = $stmt->fetchAll();
            $items = [];
            foreach($res as $row) {
                $dto = $this->setDto($row);
                $items[] = $dto;
            }
            if($items){
                return $items;
            }else{
                throw new OriginalException('取得に失敗しました。',444);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 商品の販売数と在庫数を更新
     * $itemCodeをキーにitem_salesに$itemCountを加算、item_stockに$itemCountを減算
     * @throws PDOException
     * @throws OriginalException(更新失敗時:code222)
     */
    public function insertItemSales($itemCount, $itemCode){
        try{
            $sql ="UPDATE items SET item_sales = item_sales+?, item_stock = item_stock-? where item_code = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemCount, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $itemCount, \PDO::PARAM_INT);
            $stmt->bindvalue(3, $itemCode, \PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('更新に失敗しました。',222);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
}
?>