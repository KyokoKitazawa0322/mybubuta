<?php
namespace Models;

use \Models\OrderDetailDto;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;

    
class OrderDetailDao{
    
    private $pdo = NULL;
    
    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    
    
    /**
     * 購入履歴詳細の登録
     * @param int $customerId　カスタマーID
     * @param int $orderId　OrderHistoryテーブル登録時に自動発行される注文ID
     * @param string $itemCode　商品コード
     * @param int $itemQuantity　商品点数
     * @param int $itemPrice　商品価格
     * @param int $itemTax 消費税
     * @throws MyPDOException 
     */
    public function insertOrderDetail($orderId, $itemCode, $itemQuantity, $itemPrice, $itemTax){
        try{
            $sql ="INSERT INTO order_detail(order_id, item_code, item_quantity, item_price, item_tax)VALUES(?,?,?,?,?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $orderId, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $itemCode, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $itemQuantity, \PDO::PARAM_INT);
            $stmt->bindvalue(4, $itemPrice, \PDO::PARAM_INT);
            $stmt->bindvalue(5, $itemTax, \PDO::PARAM_INT);
            $stmt->execute(); 
            
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 購入履歴詳細
     * $orderIdをキーに購入履歴詳細を取得する。
     * @param int $orderId　注文ID
     * @return OrderDetailDto[]
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function getOrderDetail($orderId){

        try{
            $sql = "SELECT items.item_name, items.item_image_path, order_detail.item_quantity, order_detail.item_price, order_detail.item_tax FROM items LEFT JOIN order_detail ON items.item_code = order_detail.item_code WHERE order_detail.order_id=?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $orderId);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if($result){
                
                $orderDetail = [];
                
                foreach($result as $row){
                    
                    $dto = new OrderDetailDto();
                    $dto->setItemName($row['item_name']);
                    $dto->setItemImagePath($row['item_image_path']);
                    $dto->setItemQuantity($row['item_quantity']);
                    $dto->setitemPrice($row['item_price']);
                    $dto->setItemTax($row['item_tax']);
                    
                    $orderDetail[] = $dto;
                }
                return $orderDetail;
                
            }else{
                $result=preg_replace("/order_detail.order_id=\?/", 'order_detail.order_id='.$orderId, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 購入履歴詳(日付指定)細取得
     * @return OrderDetailDto[] || FALSE
     * @throws MyPDOException 
     */
    public function getOrderDetailByDate($date){
    
        try{
            $sql = "SELECT a.item_name, a.item_code, c.customer_id, b.order_id, b.item_quantity, b.item_price, b.item_tax, c.purchase_date FROM items a LEFT JOIN order_detail b ON a.item_code = b.item_code LEFT JOIN order_history c ON c.order_id = b.order_id WHERE DATE_FORMAT(c.purchase_date, '%Y-%m-%d')=?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $date, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if($result){
                
                $orderDetail = [];
                
                foreach($result as $row){
                    
                    $dto = new OrderDetailDto();
                    $dto->setItemName($row['item_name']);
                    $dto->setItemCode($row['item_code']);
                    $dto->setCustomerId($row['customer_id']);
                    $dto->setOrderId($row['order_id']);
                    $dto->setItemQuantity($row['item_quantity']);
                    $dto->setItemPrice($row['item_price']);
                    $dto->setItemTax($row['item_tax']);
                    $dto->setPurchaseDate($row['purchase_date']);
                    $orderDetail[] = $dto;
                }
                return $orderDetail;
                
            }else{
                return FALSE;
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 購入履歴詳(月指定)細取得
     * @return OrderDetailDto[] || FALSE
     * @throws MyPDOException 
     */
    public function getOrderDetailByMonth($month){
    
        try{
            $sql = "SELECT a.item_name, a.item_code, c.customer_id, b.order_id, b.item_quantity, b.item_price, b.item_tax, c.purchase_date FROM items a LEFT JOIN order_detail b ON a.item_code = b.item_code LEFT JOIN order_history c ON c.order_id = b.order_id WHERE DATE_FORMAT(c.purchase_date, '%Y-%m')=?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $month, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if($result){
                
                $orderDetail = [];
                
                foreach($result as $row){
                    
                    $dto = new OrderDetailDto();
                    $dto->setItemName($row['item_name']);
                    $dto->setItemCode($row['item_code']);
                    $dto->setCustomerId($row['customer_id']);
                    $dto->setOrderId($row['order_id']);
                    $dto->setItemQuantity($row['item_quantity']);
                    $dto->setItemPrice($row['item_price']);
                    $dto->setItemTax($row['item_tax']);
                    $dto->setPurchaseDate($row['purchase_date']);
                    $orderDetail[] = $dto;
                }
                return $orderDetail;
                
            }else{
                return FALSE;
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 購入履歴詳(期間指定)細取得
     * @return OrderDetailDto[] || FALSE
     * @throws MyPDOException 
     */
    public function getOrderDetailByTerm($dateTime_1, $dateTime_2){
    
        try{
            $sql = "SELECT a.item_name, a.item_code, c.customer_id, b.order_id, b.item_quantity, b.item_price, b.item_tax, c.purchase_date FROM items a LEFT JOIN order_detail b ON a.item_code = b.item_code LEFT JOIN order_history c ON c.order_id = b.order_id WHERE DATE_FORMAT(c.purchase_date, '%Y-%m-%d') BETWEEN ? AND ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $dateTime_1, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $dateTime_2, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if($result){
                
                $orderDetail = [];
                
                foreach($result as $row){
                    
                    $dto = new OrderDetailDto();
                    $dto->setItemName($row['item_name']);
                    $dto->setItemCode($row['item_code']);
                    $dto->setCustomerId($row['customer_id']);
                    $dto->setOrderId($row['order_id']);
                    $dto->setItemQuantity($row['item_quantity']);
                    $dto->setItemPrice($row['item_price']);
                    $dto->setItemTax($row['item_tax']);
                    $dto->setPurchaseDate($row['purchase_date']);
                    $orderDetail[] = $dto;
                }
                return $orderDetail;
                
            }else{
                return FALSE;
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    

}

?>