<?php
namespace Models;

use \Models\OrderHistoryDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;
    
class OrderHistoryDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * select文共通処理
     * @return OrderHistoryDto[]
     * 例外処理は呼び出し元で行う
     */
    public function select($sql){
        $stmt = $this->pdo->query($sql); 
        $res = $stmt->fetchAll();
        $orders = [];
        foreach($res as $row) {
            $dto = $this->setDto($row);
            $orders[] = $dto;
        }
        return $orders;
    }
    
    /**
     * orderHistoryDtoにSQL取得値をセット
     * @param Array $res　SQL取得結果
     * @return OrderHistoryDto
     * 例外処理は呼び出し元のメソッドで実施
     */
    public function setDto($res){
        
        $dto = new OrderHistoryDto();
        
        $dto->setCustomerId($res['customer_id']);
        $dto->setOrderId($res['order_id']);
        $dto->setTotalAmount($res['total_amount']);
        $dto->setTotalQuantity($res['total_quantity']);
        $dto->setTax($res['tax']); 
        $dto->setPostage($res['postage']); 
        $dto->setPaymentTerm($res['payment_term']); 
        $dto->setDeliveryName($res['delivery_name']); 
        $dto->setDeliveryPost($res['delivery_post']); 
        $dto->setDeliveryAddr($res['delivery_addr']); 
        $dto->setDeliveryTel($res['delivery_tel']); 
        $dto->setPurchaseDate($res['purchase_date']); 
        
        return $dto;
    }
    
    /**
     * 購入履歴の登録
     * @param int $customerId　カスタマーID
     * @param string $totalAmount　合計金額
     * @param string $totalQuantity　合計点数
     * @param string $tax　消費税額
     * @param string $postage　送料(自動計算)
     * @param string paymentTerm　支払い方法(選択)
     * @param string $delivery_name　ユーザーの名前
     * @param string $address　ユーザーの住所(郵便番号以外)
     * @param string $post　ユーザの郵便番号
     * @param string $tel　ユーザーの電話番号
     * @throws MyPDOException 
     */
    public function insertOrderHistory($customerId, $totalAmount, $totalQuantity, $tax, $postage, $paymentTerm, $name, $address, $post, $tel){
        
        $dateTime = Config::getDateTime();
        
        try{
            $sql ="INSERT INTO order_history(customer_id, total_amount, total_quantity, tax, postage, payment_term, delivery_name, delivery_addr, delivery_post, delivery_tel, purchase_date)VALUES(?,?,?,?,?,?,?,?,?,?,?)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $totalAmount, \PDO::PARAM_INT);
            $stmt->bindvalue(3, $totalQuantity, \PDO::PARAM_INT);
            $stmt->bindvalue(4, $tax, \PDO::PARAM_INT);
            $stmt->bindvalue(5, $postage, \PDO::PARAM_INT);
            $stmt->bindvalue(6, $paymentTerm, \PDO::PARAM_STR);
            $stmt->bindvalue(7, $name, \PDO::PARAM_STR);
            $stmt->bindvalue(8, $address, \PDO::PARAM_STR);
            $stmt->bindvalue(9, $post, \PDO::PARAM_STR);
            $stmt->bindvalue(10, $tel, \PDO::PARAM_STR);
            $stmt->bindvalue(11, $dateTime, \PDO::PARAM_STR);
                
            $stmt->execute();

        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 全購入履歴取得
     * $cutomerIdをキーにカスタマー情報を購入日の新しい順に取得する。
     * @param int $customerId　カスタマーID
     * @return OrderHistoryDto[] | boolean FALSE
     * @throws MyPDOException 
     */
    public function  getAllOrderHistory($customerId){
            
        try{
            $sql = "SELECT CAST(purchase_date AS DATE) AS date, order_id, total_amount, payment_term FROM order_history WHERE customer_id=? ORDER BY purchase_date DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetchAll();
            if($res){
                $orders = [];
                foreach($res as $row){
                    $dto = new OrderHistoryDto();
                    $dto->setOrderId($row['order_id']);
                    $dto->setTotalAmount($row['total_amount']);
                    $dto->setPaymentTerm($row['payment_term']); 
                    $dto->setPurchaseDate($row['date']); 
                    $dto->setCustomerId($customerId);
                    $orders[] = $dto;
                }
                return $orders;
            }else{
                return FALSE;   
            }
         }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 購入履歴取得
     * $cutomerIdと$orderIdキーに購入情報詳細を取得する。
     * @param int $customerId　カスタマーID
     * @param int $orderId　注文ID
     * @return OrderHistoryDto
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function getOrderHistory($customerId, $orderId){

        try{
            $sql = "SELECT * FROM order_history WHERE customer_id=? && order_id=?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $orderId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;
            }else{
                $pattern = array("/customer_id=\?/", "/order_id=\?/");
                $replace = array('customer_id='.$customerId, 'order_id='.$orderId);

                $result=preg_replace($pattern, $replace, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * INSERT時に自動発行される注文idを取得
     * $cutomerIdをキーに取得する。
     * @param int $customerId　カスタマーID
     * @return OrderHistoryDto
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function getOrderId($customerId){

        try{
            $sql = "SELECT order_id FROM order_history WHERE customer_id=? ORDER BY purchase_date DESC LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = new OrderHistoryDto();
                $dto->setOrderId($res['order_id']);
                return $dto;
            }else{
                $result=preg_replace("/customer_id=\?/", 'customer_id='.$customerId, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 全購入履歴を取得
     * @return OrderHistoryDto[]
     * @throws MyPDOException
     * @throws NoRecordException
     */
    public function getOrdersAll() {
        
        try{
            $sql = "SELECT * FROM order_history ORDER BY purchase_date DESC";
            $orders = $this->select($sql);
            if($orders){
                return $orders;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 全購入履歴(purchase_date昇順)を取得
     * @return OrderHistoryDto[]
     * @throws MyPDOException
     * @throws NoRecordException
     */
    public function getOrdersAllSortByPurchaseDateASC() {
        
        try{
            $sql = "SELECT * FROM order_history ORDER BY purchase_date ASC";
            $orders = $this->select($sql);
            if($orders){
                return $orders;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 全購入履歴(purchase_date降順)を取得
     * @return OrderHistoryDto[]
     * @throws MyPDOException
     * @throws NoRecordException
     */
    public function getOrdersAllSortByPurchaseDateDESC() {
        
        try{
            $sql = "SELECT * FROM order_history ORDER BY purchase_date DESC";
            $orders = $this->select($sql);
            if($orders){
                return $orders;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 全購入履歴(total_amount昇順)を取得
     * @return OrderHistoryDto[]
     * @throws MyPDOException
     * @throws NoRecordException
     */
    public function getOrdersAllSortByTotalAmountASC() {
        
        try{
            $sql = "SELECT * FROM order_history ORDER BY total_amount ASC";
            $orders = $this->select($sql);
            if($orders){
                return $orders;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 全購入履歴(total_amount降順)を取得
     * @return OrderHistoryDto[]
     * @throws MyPDOException
     * @throws NoRecordException
     */
    public function getOrdersAllSortByTotalAmountDESC() {
        
        try{
            $sql = "SELECT * FROM order_history ORDER BY total_amount DESC";
            $orders = $this->select($sql);
            if($orders){
                return $orders;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 全購入履歴(total_quantity昇順)を取得
     * @return OrderHistoryDto[]
     * @throws MyPDOException
     * @throws NoRecordException
     */
    public function getOrdersAllSortByTotalQuantityASC() {
        
        try{
            $sql = "SELECT * FROM order_history ORDER BY total_quantity ASC";
            $orders = $this->select($sql);
            if($orders){
                return $orders;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 全購入履歴(total_quantity降順)を取得
     * @return OrderHistoryDto[]
     * @throws MyPDOException
     * @throws NoRecordException
     */
    public function getOrdersAllSortByTotalQuantityDESC() {
        
        try{
            $sql = "SELECT * FROM order_history ORDER BY total_quantity DESC";
            $orders = $this->select($sql);
            if($orders){
                return $orders;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 購入履歴総計(月指定)を取得
     * @return OrderHistoryDto || FALSE
     * @throws MyPDOException
     */
    public function getOrderHistoryByMonth($month) {
        
        try{
            $sql = "SELECT DATE_FORMAT(purchase_date, '%Y年%m月') AS month, SUM(CASE WHEN DATE_FORMAT(purchase_date, '%Y-%m')=? THEN total_quantity ELSE 0 END) AS total_quantity_by_month, SUM(CASE WHEN DATE_FORMAT(purchase_date, '%Y-%m')=? THEN total_amount ELSE 0 END) AS total_amount_by_month FROM order_history;";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $month, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $month, \PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = new OrderHistoryDto();
                $dto->setTotalQuantityByTerm($res['total_quantity_by_month']);
                $dto->setTotalAmountByTerm($res['total_amount_by_month']);
                return $dto;
            }else{
                return FALSE;
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 購入履歴総計(日付指定)を取得
     * @return OrderHistoryDto || FALSE
     * @throws MyPDOException
     */
    public function getOrderHistoryByDate($date) {
        
        try{
            $sql = "SELECT SUM(CASE WHEN DATE_FORMAT(purchase_date, '%Y-%m-%d')=? THEN total_quantity ELSE 0 END) AS total_quantity_by_date, SUM(CASE WHEN DATE_FORMAT(purchase_date, '%Y-%m-%d')=? THEN total_amount ELSE 0 END) AS total_amount_by_date FROM order_history;";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $date, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $date, \PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = new OrderHistoryDto();
                $dto->setTotalQuantityByTerm($res['total_quantity_by_date']);
                $dto->setTotalAmountByTerm($res['total_amount_by_date']);
                return $dto;
            }else{
                return FALSE;
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 購入履歴総計(期間指定)を取得
     * @return OrderHistoryDto || FALSE
     * @throws MyPDOException
     */
    public function getOrderHistoryByTerm($date_1, $date_2) {
        
        try{
            $sql = "SELECT SUM(CASE WHEN DATE_FORMAT(purchase_date, '%Y-%m-%d') BETWEEN ? AND ? THEN total_quantity ELSE 0 END) AS total_quantity_by_term, SUM(CASE WHEN DATE_FORMAT(purchase_date, '%Y-%m-%d') BETWEEN ? AND ? THEN total_amount ELSE 0 END) AS total_amount_by_term FROM order_history;";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $date_1, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $date_2, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $date_1, \PDO::PARAM_STR);
            $stmt->bindvalue(4, $date_2, \PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = new OrderHistoryDto();
                $dto->setTotalQuantityByTerm($res['total_quantity_by_term']);
                $dto->setTotalAmountByTerm($res['total_amount_by_term']);
                return $dto;
            }else{
                return FALSE;
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
        
        
}

?>