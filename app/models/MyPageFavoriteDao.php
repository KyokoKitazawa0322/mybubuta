<?php
namespace Models;
use \Models\FavoriteDto;

class MyPageFavoriteDao extends \Models\Model { 

    public function __construct(){
        parent::__construct();
    }
    
    /** @return ItemsDto */
    public function getFavoriteAll($customer_id){

        $sql = "select  items.item_code, items.item_name, items.item_image, items.item_price, items.tax FROM items left join favorite on items.item_code = favorite.item_code where favorite.customer_id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customer_id);  
        $stmt->execute();
        $res = $stmt->fetchAll();
        if($res){
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
        }else{
            return false;   
        }
    }
    
    public function insertIntoFavorite($item_code, $customer_id){
        $sql = "select * from favorite where item_code=? && customer_id=? ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $item_code, \PDO::PARAM_INT);
        $stmt->bindvalue(2, $customer_id, \PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch();
        if(!$res){
            $sql = "insert into favorite(item_code, customer_id) values(?,?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $item_code, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $customer_id, \PDO::PARAM_INT);
            $stmt->execute();
        }   
    }
    
    public function deleteFavorite($item_code, $customer_id){
        
        $sql = "delete from favorite where item_code = ? && customer_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $item_code, \PDO::PARAM_INT);
        $stmt->bindvalue(2, $customer_id, \PDO::PARAM_INT);  
        $stmt->execute();     
    }
}
    
?>