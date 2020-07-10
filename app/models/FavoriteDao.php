<?php
namespace Models;

use \Models\FavoriteDto;
use \Models\ItemsDao;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;

class FavoriteDao{ 

    private $pdo = NULL;
    
    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    
    /**
     * item_codeが不正な値ではないか確認
     * $itemCodeをキーに商品情報を取得する。
     * @param string $itemCode 商品コード
     * @return bool TRUE | FALSE
     * 例外処理は呼び出し元で行う。
     */
    public function checkItemByItemCode($itemCode){
        
        $dto = new ItemsDto();
        $sql = "SELECT * FROM items WHERE item_code=? && delete_flag = FALSE";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
        $stmt->execute();   
        $res = $stmt->fetch();
        if($res){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * お気に入り商品を全部取得
     * $customerIdをキーに商品情報を取得する。
     * @param int $customerId　カスタマーID
     * @return ItemsDto[] | boolean FALSE
     * @throws MyPDOException
     */
    public function getFavoriteAll($customerId){
        try{
            $sql = "SELECT  items.item_code, items.item_name, items.item_image_path, items.item_price, items.item_tax, items.item_status FROM items left join favorite on items.item_code = favorite.item_code WHERE favorite.customer_id=?";

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
                    $dto->setItemImagePath($row['item_image_path']);
                    $dto->setItemPrice($row['item_price']);
                    $dto->setItemTax($row['item_tax']);
                    $dto->setItemStatus($row['item_status']);
                    $items[] = $dto; 
                }
                return $items;
            }else{
                return FALSE;   
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * お気に入り商品を登録
     * 商品コードが不正な値でない場合のみ処理を進める
     * 既に登録がない(=select文の結果FALSE)場合のみ登録処理
     * $itemCodeと$customerIdをキーに商品情報を登録する。
     * @param string $itemCode 　 商品コード    
     * @param int $customerId　カスタマーID
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function insertIntoFavorite($itemCode, $customerId){
        try{
            /*- 商品コードが存在するか確認 -*/
            $res = $this->checkItemByItemCode($itemCode);
            
            /*- 商品コードが不正な値でない場合のみ処理を進める -*/
            if($res){
                $sql_1 = "SELECT * FROM favorite WHERE item_code=? && customer_id=? ";
                $stmt = $this->pdo->prepare($sql_1);
                $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
                $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
                $stmt->execute();

                $res = $stmt->fetch();

                /*- 既に登録がない場合のみ登録処理 -*/
                if(!$res){
                    $sql_2 = "INSERT INTO favorite(item_code, customer_id) VALUES(?,?)";
                    $stmt = $this->pdo->prepare($sql_2);
                    $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
                    $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
                    $stmt->execute();

                    $count = $stmt->rowCount();
                }
            }else{
                throw new DBParamException("invalid param error:SELECT * FROM items WHERE item_code={$itemCode} && delete_flag = FALSE");
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }   
    }
  
    /**
     * お気に入り商品を削除
     * $itemCodeと$customerIdをキーに商品情報を削除する。
     * @param int $customerId　カスタマーID
     * @param string $itemCode 商品コード
     * @throws MyPDOException
     * @throws DBParamException
     */
    public function deleteFavorite($itemCode, $customerId){
        try{
            /*- 商品コードが存在するか確認 -*/
            $res = $this->checkItemByItemCode($itemCode);
            
            /*- 商品コードが不正な値でない場合のみ処理を進める -*/
            if($res){
                $sql = "DELETE FROM favorite WHERE item_code=? && customer_id=?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
                $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);  
                $stmt->execute();     

                $count = $stmt->rowCount();
                if($count<1){
                    $pattern=array("/item_code=\?/", "/customer_id=\?/");
                    $replace=array('item_code='.$itemCode, 'customer_id='.$customerId);

                    $result=preg_replace($pattern, $replace, $sql);
                    throw new DBParamException("invalid param error:".$result);
                }
            }else{
                throw new DBParamException("invalid param error:SELECT * FROM items WHERE item_code={$itemCode} && delete_flag = FALSE");
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}
    
?>