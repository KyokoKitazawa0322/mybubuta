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
     * @return ItemsDto[]
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
            $dto = $this->setDto($res);
            
            if($dto){
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
            if(!empty($categories)){
                $category = "";
                foreach($categories as $key){
                    $category = $category.':'.$key.',';
                }
                $category = preg_replace("/,$/", "", $category);
                $sql = $sql."AND item_category IN ($category) ";
            }
            if(!empty($keyWord)){
                $sql = $sql."AND item_name LIKE :keyword ";
            }        
            if(!empty($minPrice)){
                $sql = $sql."AND item_price+tax >= :minprice ";
            }
            if(!empty($maxPrice)){
                $sql = $sql."AND item_price+tax <= :maxprice ";
            }
            if($sortKey == "01"){
                $sql = $sql."ORDER BY item_price asc";
            }elseif($sortKey == "02"){
                $sql = $sql."ORDER BY item_price desc";
            }else{     
                $$sql = $sql."ORDER BY item_insert_date asc";
            }

            $stmt = $this->pdo->prepare($sql);

            if(!empty($categories)){
                foreach($categories as $category){
                    $stmt->bindvalue(":{$category}", $category, \PDO::PARAM_STR);
                    //IN (:skirt, :tops, :dress)-> 「''」がはいるとエスケープされるためバラバラに定義
                }
            }
            if(!empty($keyWord)){
                $stmt->bindvalue(":keyword", '%'.$keyWord.'%', \PDO::PARAM_STR);
            }
            if(!empty($minPrice)){
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
            $sql = "SELECT A.item_code, A.item_name, A.item_image, A.item_price, A.tax, COUNT(B.item_code) AS '販売数量' FROM items AS A LEFT JOIN order_detail AS B ON A.item_code = B.item_code GROUP by A.item_code ORDER BY 販売数量 DESC LIMIT 5";

            $stmt = $this->pdo->query($sql); 
            $res = $stmt->fetchAll();
            $items = [];

            foreach($res as $row) {
                $dto = new ItemsDto();

                $dto->setItemCode($row['item_code']);
                $dto->setItemName($row['item_name']);
                $dto->setItemImage($row['item_image']);
                $dto->setItemPrice($row['item_price']);
                $dto->setTax($row['tax']);

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
}
?>