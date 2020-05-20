<?php
namespace Models;
use \Models\OrderDetailDto;
    
class OrderDetailDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function setDto($dto, $res){
        $dto->setDetailId($res['detail_id']);
        $dto->setOrderId($res['order_id']);
        $dto->setItemCode($res['item_code']);
        $dto->setItemCount($res['item_count']); 
    }
    
    public function insertOrderDetail($orderId, $itemCode, $itemCount, $itemPrice, $itemTax){
        $sql ="INSERT into order_detail(order_id, item_code, item_count, item_price, item_tax)values(?,?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $orderId);
        $stmt->bindvalue(2, $itemCode);
        $stmt->bindvalue(3, $itemCount);
        $stmt->bindvalue(4, $itemPrice);
        $stmt->bindvalue(5, $itemTax);
        $result = $stmt->execute();
        if($result){
            return true;   
        }else{
            return false;   
        }
    }
    
    /** @return OrderDetailDto */
    public function getOrderDetail($orderId){
        //new
        $sql = "SELECT items.item_name, items.item_image, order_detail.item_count, order_detail.item_price, order_detail.item_tax FROM items LEFT JOIN order_detail ON items.item_code = order_detail.item_code where order_detail.order_id = ?";

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
                $dto->setItemCount($row['item_count']);
                $dto->setitemPrice($row['item_price']);
                //new
                $dto->setItemTax($row['item_tax']);
                $orderDetail[] = $dto;
            }
            return $orderDetail;
        }else{
            return false;   
        }
    }
    
}

?>