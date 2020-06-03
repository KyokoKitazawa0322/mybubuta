<?php
namespace Models;
use \Models\OrderHistoryDto;
use \Models\OriginalException;
use \Config\Config;
    
class OrderHistoryDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * orderHistoryDtoにSQL取得値をセット
     * @param Array $res　SQL取得結果
     * @return OrderHistoryDto
     * 例外処理は呼び出し元のメソッドで実施
     */
    public function setDto($res){
        
        $dto = new OrderHistoryDto();
        
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
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @param string $totalAmount　合計金額
     * @param string $totalQuantity　合計点数
     * @param string $tax　消費税額
     * @param string $postage　送料(自動計算)
     * @param string paymentTerm　支払い方法(選択)
     * @param string $delivery_name　入力されたユーザーの名前
     * @param string $address　ユーザーの住所(郵便番号以外)
     * @param string $post　ユーザの郵便番号
     * @param string $tel　ユーザーの電話番号
     * @throws PDOException 
     * @throws OriginalException(登録失敗時:code444)
     */
    public function insertOrderHistory($customerId, $totalAmount, $totalQuantity, $tax, $postage, $paymentTerm, $name, $address, $post, $tel){
        
        $dateTime = Config::getDateTime();
        
        try{
            $sql ="insert into order_history(customer_id, total_amount, total_quantity, tax, postage, payment_term, delivery_name, delivery_addr, delivery_post, delivery_tel, purchase_date)values(?,?,?,?,?,?,?,?,?,?,?)";

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
            
            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('登録に失敗しました。',444);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 全購入履歴取得
     * $cutomerIdをキーにカスタマー情報を購入日の新しい順に取得する。
     * なければfalseを返す。
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @return OrderHistoryDto[]
     * @throws PDOException 
     */
    public function  getAllOrderHistory($customerId){
            
        try{
            $sql = "SELECT CAST(purchase_date AS DATE) AS date, order_id, total_amount, payment_term FROM order_history where customer_id = ? order by purchase_date DESC";

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
                    $orders[] = $dto;
                }
                return $orders;
            }else{
                return false;   
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 購入履歴取得
     * $cutomerIdと$orderIdキーに購入情報詳細を取得する。
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @return OrderHistoryDto
     * @throws PDOException 
     * @throws OriginalException(取得失敗時:code111) 
     */
    public function getOrderHistory($customerId, $orderId){

        try{
            $sql = "SELECT * FROM order_history where customer_id = ? && order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $orderId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;
            }else{
                throw new OriginalException('取得に失敗しました。',111);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * INSERT時に自動発行される注文idを取得
     * $cutomerIdをキーに取得する。
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @return OrderHistoryDto
     * @throws PDOException 
     * @throws OriginalException(取得失敗時:code111) 
     */
    public function getOrderId($customerId){

        try{
            $sql = "SELECT order_id FROM order_history where customer_id = ? order by purchase_date DESC LIMIT 1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = new OrderHistoryDto();
                $dto->setOrderId($res['order_id']);
                return $dto;
            }else{
                throw new OriginalException('取得に失敗しました。',111);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
}

?>