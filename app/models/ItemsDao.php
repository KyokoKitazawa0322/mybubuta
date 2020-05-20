<?php
namespace Models;
use \Models\ItemsDto;

class ItemsDao extends \Models\Model { 
        
    private $sql = "SELECT * FROM items WHERE item_del_flag = '0'";

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
        $dto->setItemCategory($res['item_category']);
        $dto->setItemImage($res['item_image']);
        $dto->setItemDetail($res['item_detail']); 
    }
    
    public function findItemsExecute(){

        $stmt = $this->pdo->query($this->sql); 
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
    
    public function findItemsByCategory($category){
        $in = "";
        foreach($category as $key=>$value)
            if(isset($_GET[$key])){
                $in = "{$in}'{$key}',";
            }
        $in = preg_replace( "/,$/", "", $in );
        $this->sql = $this->sql." AND item_category IN ( $in ) ";
    }    
    
    public function findItemsByName($itemName){
      $this->sql = "{$this->sql}AND item_name LIKE '%{$itemName}%'";
    }

    public function findItemsByMinPrice($minPrice){
      $this->sql =  "{$this->sql} AND item_price >={$minPrice} ";          
    }
    
    public function findItemsByMaxPrice($maxPrice){  
        $this->sql =  "{$this->sql} AND item_price <={$maxPrice} ";    
    }

    public function findItemsByPrice($minPrice, $maxPrice){
        $this->sql = "{$this->sql} AND item_price >={$minPrice} && item_price <={$maxPrice} ";    
    }
    
    public function getSql(){
        return $this->sql;
    }

    public function itemsSortOnly($sortkey){
        if($sortkey == "01"){
        $this->sql = $this->sql."ORDER BY item_price asc";
        }
        if($sortkey == "02"){
        $this->sql = $this->sql."ORDER BY item_price desc";
        }
        if($sortkey == "03"){
        $this->sql = $this->sql."ORDER BY item_insert_date asc";
        }
    }
    
    public function itemsSort($sortkey, $sql){
        if($sortkey == "01"){
        $this->sql = $sql."ORDER BY item_price asc";
        }
        if($sortkey == "02"){
        $this->sql = $sql."ORDER BY item_price desc";
        }
        if($sortkey == "03"){
        $this->sql = $sql."ORDER BY item_insert_date asc";
        }
    }
    
    public function itemsOrder() {
        $this->sql = $this->sql."ORDER BY item_insert_date asc";
    }
    
    public function selectItemsRank() {

        $sql = "SELECT A.item_code, A.item_name, A.item_image, A.item_price, COUNT(B.item_code) AS '販売数量' FROM items AS A LEFT JOIN order_detail AS B ON A.item_code = B.item_code GROUP by A.item_code, A.item_code ORDER BY 販売数量 DESC LIMIT 5";

        $stmt = $this->pdo->query($sql); 
        $res = $stmt->fetchAll();
        $items = [];

        foreach($res as $row) {
            $dto = new ItemsDto();

            $dto->setItemCode($row['item_code']);
            $dto->setItemName($row['item_name']);
            $dto->setItemImage($row['item_image']);
            $dto->setItemPrice($row['item_price']);

            $items[] = $dto; 
        }
        return $items;
    }

}
?>