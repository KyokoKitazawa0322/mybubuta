<?php
namespace Models;
use \Models\OrderDetailDto;
use \Models\OriginalException;
    
class OrderDetailDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    
    /**
     * 購入履歴詳細の登録
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @param int $orderId　OrderHistoryテーブル登録時に自動発行される注文ID
     * @param string $itemCode　商品コード
     * @param int $itemQuantity　商品点数
     * @param int $itemPrice　商品価格
     * @param int $itemTax 消費税
     * @throws PDOException 
     * @throws OriginalException(登録失敗時:code444)
     */
    public function insertOrderDetail($orderId, $itemCode, $itemQuantity, $itemPrice, $itemTax){
        try{
            $sql ="INSERT into order_detail(order_id, item_code, item_quantity, item_price, item_tax)values(?,?,?,?,?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $orderId, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $itemCode, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $itemQuantity, \PDO::PARAM_INT);
            $stmt->bindvalue(4, $itemPrice, \PDO::PARAM_INT);
            $stmt->bindvalue(5, $itemTax, \PDO::PARAM_INT);
            $stmt->execute();

            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('登録に失敗しました。',444);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 購入履歴詳細
     * $orderIdをキーに購入履歴詳細を取得する。
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @return OrderDetailDto[]
     * @throws PDOException 
     */
    public function getOrderDetail($orderId){

        try{
            $sql = "SELECT items.item_name, items.item_image, order_detail.item_quantity, order_detail.item_price, order_detail.item_tax FROM items LEFT JOIN order_detail ON items.item_code = order_detail.item_code where order_detail.order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $orderId);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if($result){
                $orderDetail = [];
                foreach($result as $row){
                    $dto = new OrderDetailDto();
                    $dto->setItemName($row['item_name']);
                    $dto->setItemImage($row['item_image']);
                    $dto->setItemQuantity($row['item_quantity']);
                    $dto->setitemPrice($row['item_price']);
                    $dto->setItemTax($row['item_tax']);
                    $orderDetail[] = $dto;
                }
                return $orderDetail;
            }else{
                throw new OriginalException('取得に失敗しました。',111);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
}

?>