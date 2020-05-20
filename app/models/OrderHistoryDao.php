<?php
namespace Models;
use \Models\OrderHistoryDto;
    
class OrderHistoryDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function setDto($dto, $res){
        $dto->setOrderId($res['order_id']);
        $dto->setTotalPayment($res['total_payment']);
        $dto->setTotalAmount($res['total_amount']);
        $dto->setTax($res['tax']); 
        $dto->setPostage($res['postage']); 
        $dto->setPayment($res['payment']); 
        $dto->setDeliveryName($res['delivery_name']); 
        $dto->setDeliveryPost($res['delivery_post']); 
        $dto->setDeliveryAddr($res['delivery_addr']); 
        $dto->setDeliveryTel($res['delivery_tel']); 
        $dto->setPurchaseDate($res['purchase_date']); 
    }
    
    public function insertOrderHistory($customerId, $totalPayment, $totalAmount, $tax, $postage, $payment, $name, $address, $post, $tel){
        //配送先情報を連結して変数に格納
        $sql ="insert into order_history(customer_id, total_payment, total_amount, tax, postage, payment, delivery_name, delivery_addr, delivery_post, delivery_tel, purchase_date)values(?,?,?,?,?,?,?,?,?,?,now())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId);
        $stmt->bindvalue(2, $totalPayment);
        $stmt->bindvalue(3, $totalAmount);
        $stmt->bindvalue(4, $tax);
        $stmt->bindvalue(5, $postage);
        $stmt->bindvalue(6, $payment);
        $stmt->bindvalue(7, $name);
        $stmt->bindvalue(8, $address);
        $stmt->bindvalue(9, $post);
        $stmt->bindvalue(10,$tel);
        $result = $stmt->execute();
    }
    
    /** @return OrderHistoryDto */
    public function  getAllOrderHistory($customerId){
            
        $sql = "SELECT CAST(purchase_date AS DATE) AS date, order_id, total_payment, payment FROM order_history where customer_id = ? order by purchase_date DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId);
        $stmt->execute();
        $res = $stmt->fetchAll();
        if($res){
            $orders = [];
            foreach($res as $row){
                $dto = new OrderHistoryDto();
                
                $dto->setOrderId($row['order_id']);
                $dto->setTotalPayment($row['total_payment']);
                $dto->setPayment($row['payment']); 
                $dto->setPurchaseDate($row['date']); 
                $orders[] = $dto;
            }
            return $orders;
        }else{
            return false;   
        }
    }
    
    /** @return OrderHistoryDto */
    public function getOrderHistory($customerId, $orderId){

        $sql = "SELECT * FROM order_history where customer_id = ? && order_id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId);
        $stmt->bindvalue(2, $orderId);
        $stmt->execute();
        $result = $stmt->fetch();
        if($result){
            $dto = new OrderHistoryDto();
            $this->setDto($dto, $result);
            return $dto;
        }else{
            return false;   
        }
    }
    
    public function getOrderId($customerId){

        //INSERT時に自動発行される注文idを取得し購入アイテムを全件明細テーブルに登録
        $sql = "SELECT order_id FROM order_history where customer_id = ? order by purchase_date DESC LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId);
        $stmt->execute();
        $res = $stmt->fetch();
        return $res;
    }
}

?>