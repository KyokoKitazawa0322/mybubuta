<?php
namespace Models;
use \Models\CustomerDto;

class CustomerDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    //ログイン認証
    public function getCustomerByMail($mail){

        $dto = new CustomerDto();

        $sql = "SELECT * FROM customers WHERE mail=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $mail);
        $stmt->execute();
        $res = $stmt->fetch();
        if($res){
            $this->setDto($dto, $res);
            return $dto;
        }else{
            return false;
        }
    }
    
    public function checkMailExists($mail){

        $sql = "SELECT * FROM customers WHERE mail = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $mail);
        $stmt->execute();
        $res = $stmt->fetch();
        if($res){
            $dto = new customerDto();
            $dto->setMail($res['mail']);
            return $dto;
        }else{
            return false;
        }
    }
    
    public function setDto($dto, $res){
        $dto->setCustomerId($res['customer_id']);
        $dto->setHashPassword($res['hash_password']);
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
        $dto->setMail($res['mail']);
        $dto->setDelFlag($res['del_flag']);
    }
    
    //会員情報登録
    public function insertCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $mail){

        $sql = "insert into customers(last_name, first_name, ruby_last_name, ruby_first_name, address_01, address_02, address_03, address_04, address_05, address_06, tel, mail, hash_password, del_flag, customer_insert_date)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,now())";

        $hash_pass = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $lastName);
        $stmt->bindvalue(2, $firstName);
        $stmt->bindvalue(3, $rubyLastName);
        $stmt->bindvalue(4, $rubyFirstName);
        $stmt->bindvalue(5, $address01);
        $stmt->bindvalue(6, $address02);
        $stmt->bindvalue(7, $address03);
        $stmt->bindvalue(8, $address04);
        $stmt->bindvalue(9, $address05);
        $stmt->bindvalue(10, $address06);
        $stmt->bindvalue(11, $tel);
        $stmt->bindvalue(12, $mail);
        $stmt->bindvalue(13, $hash_pass);
        $stmt->bindvalue(14, '0');
        $stmt->execute();
    }
    
    //会員情報の住所を配送先住所に設定
    public function setDeliveryDefault($customerId){

        $sql = "UPDATE customers SET del_flag=? where customer_id=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, '0');
        $stmt->bindvalue(2, $customerId);
        $stmt->execute();  
    }
    
    //会員情報の住所をいつもの配送先住所からはずす
    public function releaseDeliveryDefault($customerId){

        $sql = "UPDATE customers SET del_flag=? where customer_id=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, '1');
        $stmt->bindvalue(2, $customerId);
        $stmt->execute();  
    }
    
    //会員情報削除
    public function deleteCustomerInfo($customerId){

        $sql = "DELETE from customers where customer_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId);
        $stmt->execute();
    }
    
    /** @return CustomerDto */
    public function getCustomerById($customerId){

        $sql = "SELECT * FROM customers WHERE customer_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId);
        $stmt->execute();
        $res = $stmt->fetch();
        if($res){
            $dto = new CustomerDto();
            $this->setDto($dto, $res);
            return $dto;
        }else {
            return false;    
        }
    }
    
    public function updateCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $mail, $customerId){

        $hash_pass = password_hash($password, PASSWORD_DEFAULT);
        
        $sql ="UPDATE customers SET last_name=?, first_name=?, ruby_last_name=?, ruby_first_name=?, address_01=?, address_02=?, address_03=?, address_04=?, address_05=?, address_06=?, tel=?, mail=?, hash_password=?, customer_updated_date=now() where customer_id=?";
        
        $stmt = $this->pdo->prepare($sql); 
        
        $stmt->bindvalue(1, $lastName);
        $stmt->bindvalue(2, $firstName);
        $stmt->bindvalue(3, $rubyLastName);
        $stmt->bindvalue(4, $rubyFirstName);
        $stmt->bindvalue(5, $address01);
        $stmt->bindvalue(6, $address02);
        $stmt->bindvalue(7, $address03);
        $stmt->bindvalue(8, $address04);
        $stmt->bindvalue(9, $address05);
        $stmt->bindvalue(10, $address06);
        $stmt->bindvalue(11, $tel);
        $stmt->bindvalue(12, $mail);
        $stmt->bindvalue(13, $hash_pass);
        $stmt->bindvalue(14, $customerId);
        $stmt->execute();
    }
}

?>