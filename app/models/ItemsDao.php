<?php
namespace Models;
use \Models\ItemsDto;

class ItemsDao extends \Models\Model { 

    public function __construct(){
        parent::__construct();
    }
    
    public function findItemByItemCode($itemCode){
        $dto = new ItemsDto();
        $sql = "SELECT * FROM items WHERE item_code = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $itemCode); 
        $stmt->execute();   
        $res = $stmt->fetch();
        if($res){
            $this->setDto($dto, $res);
            return $dto;
        }else {
            return false;    
        }
    }
        
    public function setDto($dto, $res){
        $dto->setItemCode($res['item_code']);
        $dto->setItemName($res['item_name']);
        $dto->setItemPrice($res['item_price']);
        $dto->setTax($res['tax']);
        $dto->setItemCategory($res['item_category']);
        $dto->setItemImage($res['item_image']);
        $dto->setItemDetail($res['item_detail']); 
    }
        
    public function test($categories, $keyWord, $minPrice, $maxPrice, $sortKey){
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
                $dto = new ItemsDto();
                $this->setDto($dto, $row);
                $items[] = $dto;
            }
            return $items;
        }else {
            return false;   
        }
    }
    
    public function selectItemsRank() {

        $sql = "SELECT A.item_code, A.item_name, A.item_image, A.item_price, A.tax, COUNT(B.item_code) AS '販売数量' FROM items AS A LEFT JOIN order_detail AS B ON A.item_code = B.item_code GROUP by A.item_code, A.item_code ORDER BY 販売数量 DESC LIMIT 5";

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
        return $items;
    }
}
?>