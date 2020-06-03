<?php
namespace Models;
use \Models\FavoriteDto;

class MyPageFavoriteDao extends \Models\Model { 

    public function __construct(){
        parent::__construct();
    }
    
    /**
     * お気に入り商品を全部取得
     * $customerIdをキーに商品情報を取得する。
     * なければfalseを返す
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @return ItemsDto[]
     * @throws PDOException
     */
    public function getFavoriteAll($customerId){
        try{
            $sql = "select  items.item_code, items.item_name, items.item_image, items.item_price, items.item_tax FROM items left join favorite on items.item_code = favorite.item_code where favorite.customer_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId);  
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
                    $dto->setItemTax($row['item_tax']);
                    $items[] = $dto; 
                }
                return $items;
            }else{
                return false;   
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * お気に入り商品を登録
     * 既に登録がない(=select文の結果false)場合のみ登録処理
     * $itemCodeと$customerIdをキーに商品情報を登録する。
     * @param string $itemCode 　 商品コード    
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException
     * @throws OriginalException(登録失敗時:code444)
     */
    public function insertIntoFavorite($itemCode, $customerId){
        try{
            $sql = "select * from favorite where item_code=? && customer_id=? ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            
            if(!$res){
                $sql = "insert into favorite(item_code, customer_id) values(?,?)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
                $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
                $stmt->execute();
                
                $count = $stmt->rowCount();
                if($count<1){
                    throw new \Models\OriginalException('登録に失敗しました。',444);
                }
            }
        }catch(\PDOException $e){
            throw $e;
        }   
    }
  
    /**
     * お気に入り商品を削除
     * $itemCodeと$customerIdをキーに商品情報を削除する。
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @param string $itemCode 　 商品コード
     * @throws PDOException
     * @throws OriginalException(削除失敗時:code333)
     */
    public function deleteFavorite($itemCode, $customerId){
        try{
            $sql = "delete from favorite where item_code = ? && customer_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);  
            $stmt->execute();     
        
            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('削除に失敗しました。',333);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
}
    
?>