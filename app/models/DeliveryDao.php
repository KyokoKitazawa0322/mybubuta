<?php
namespace Models;
use \Models\DeliveryDto;
    
class DeliveryDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function setDto($res){
        
        $dto = new DeliveryDto();
        
        $dto->setDeliveryId($res['delivery_id']);
        $dto->setLastName($res['last_name']);
        $dto->setFirstName($res['first_name']);
        $dto->setRubyLastName($res['ruby_last_name']); 
        $dto->setRubyFirstName($res['ruby_first_name']); 
        $dto->setAddress01($res['address_01']); 
        $dto->setAddress02($res['address_02']); 
        $dto->setAddress03($res['address_03']); 
        $dto->setAddress04($res['address_04']); 
        $dto->setAddress05($res['address_05']); 
        $dto->setAddress06($res['address_06']); 
        $dto->setTel($res['tel']);
        $dto->setDelFlag($res['del_flag']);
        
        return $dto;
    }
    
    //配送先住所登録
    public function insertDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $customerId){
        
        $sql ="INSERT INTO delivery(last_name, first_name, ruby_last_name, ruby_first_name, address_01, address_02, address_03, address_04, address_05, address_06, tel, customer_id, del_flag, delivery_insert_date)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,now())";
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $lastName, \PDO::PARAM_STR);
        $stmt->bindvalue(2, $firstName, \PDO::PARAM_STR);
        $stmt->bindvalue(3, $rubyLastName, \PDO::PARAM_STR);
        $stmt->bindvalue(4, $rubyFirstName, \PDO::PARAM_STR);
        $stmt->bindvalue(5, $address01, \PDO::PARAM_STR);
        $stmt->bindvalue(6, $address02, \PDO::PARAM_STR);
        $stmt->bindvalue(7, $address03, \PDO::PARAM_STR);
        $stmt->bindvalue(8, $address04, \PDO::PARAM_STR);
        $stmt->bindvalue(9, $address05, \PDO::PARAM_STR);
        $stmt->bindvalue(10, $address06, \PDO::PARAM_STR);
        $stmt->bindvalue(11, $tel, \PDO::PARAM_STR);
        $stmt->bindvalue(12, $customerId);
        $stmt->bindvalue(13, "1");
        $result = $stmt->execute();

    }
    
    //配送先住所更新
    public function updateDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $customerId, $deliveryId){

        $sql ="UPDATE delivery SET last_name=?, first_name=?, ruby_last_name=?, ruby_first_name=?, address_01=?, address_02=?, address_03=?, address_04=?, address_05=?, address_06=?, tel=?, delivery_updated_date = now() where customer_id=? && delivery_id=?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $lastName, \PDO::PARAM_STR);
        $stmt->bindvalue(2, $firstName, \PDO::PARAM_STR);
        $stmt->bindvalue(3, $rubyLastName, \PDO::PARAM_STR);
        $stmt->bindvalue(4, $rubyFirstName, \PDO::PARAM_STR);
        $stmt->bindvalue(5, $address01, \PDO::PARAM_STR);
        $stmt->bindvalue(6, $address02, \PDO::PARAM_STR);
        $stmt->bindvalue(7, $address03, \PDO::PARAM_STR);
        $stmt->bindvalue(8, $address04, \PDO::PARAM_STR);
        $stmt->bindvalue(9, $address05, \PDO::PARAM_STR);
        $stmt->bindvalue(10, $address06, \PDO::PARAM_STR);
        $stmt->bindvalue(11, $tel, \PDO::PARAM_STR);
        $stmt->bindvalue(12, $customerId, \PDO::PARAM_INT);
        $stmt->bindvalue(13, $deliveryId, \PDO::PARAM_INT);
        $result = $stmt->execute();
    }
    
    //配送先住所の既存設定解除
    public function releaseDeliveryDefault($customerId){  
        $sql ="UPDATE delivery SET del_flag=? where customer_id=? && del_flag=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, '1');
        $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
        $stmt->bindvalue(3, '0');
        $stmt->execute();
    }
    
    //配送先住所の既存設定
    public function setDeliveryDefault($customerId, $deliveryId){
        $sql ="UPDATE delivery SET del_flag=? where customer_id=? && delivery_id=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, '0');
        $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
        $stmt->bindvalue(3, $deliveryId, \PDO::PARAM_INT);
        $stmt->execute();
    }
    
    //配送先住所削除
    public function deleteDeliveryInfo($customerId, $deliveryId){
        $sql = "DELETE FROM delivery WHERE customer_id = ? && delivery_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
        $stmt->bindvalue(2, $deliveryId, \PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function getDeliveryInfo($customerId){

        $sql = "SELECT * FROM delivery WHERE customer_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId);
        $stmt->execute();
        $res = $stmt->fetchAll();
        
        $deliveries = [];
        if($res){
            foreach($res as $row){
                $dto = $this->setDto($row);
                $deliveries[] = $dto;
            }
            return $deliveries;
        }else{
            return false;   
        }
    }
    
    public function getDefDeliveryInfo($customerId){

        $sql = "SELECT * FROM delivery WHERE customer_id = ? && del_flag =?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
        $stmt->bindvalue(2, "0");
        $stmt->execute();
        $res = $stmt->fetch();
        if($res){
            $dto = $this->setDto($res);
            return $dto;
        }else {
            return false;    
        }
    }
    
    public function getDeliveryInfoById($customerId, $deliveryId){

        $sql = "SELECT * FROM delivery WHERE customer_id = ? && delivery_id =?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
        $stmt->bindvalue(2, $deliveryId, \PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch();
        if($res){
            $dto = $this->setDto($res);
            return $dto;
        }else {
            return false;    
        }
    }
}

?>