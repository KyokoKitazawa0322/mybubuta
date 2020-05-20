<?php
namespace Models;

class DeliveryDto extends \Models\Model {
    private $deliveryId;
    private $customerId;
    private $lastName;
    private $firstName;
    private $rubyLastName;
    private $rubyFirstName;
    private $address01;
    private $address02;
    private $address03;
    private $address04;
    private $address05;
    private $address06;
    private $tel;
    private $delFlag;
    private $deliveryInsertDate;
    private $deliveryUpdatedDate;
    
   public function setDeliveryId($deliveryId){
        $this->deliveryId = $deliveryId;
    }

    public function setLastName($lastName){
        $this->lastName = $lastName;
    }

    public function setFirstName($firstName){
        $this->firstName = $firstName;
    }

    public function setRubyLastName($rubyLastName){
        $this->rubyLastName = $rubyLastName;
    }

    public function setRubyFirstName($rubyFirstName){
        $this->rubyFirstName = $rubyFirstName;
    }

    public function setAddress01($address01){
        $this->address01 = $address01;
    }

    public function setAddress02($address02){
    	$this->address02 = $address02;
    }

    public function setAddress03($address03){
        $this->address03 = $address03;
    }

    public function setAddress04($address04){
        $this->address04 = $address04;
    }

    public function setAddress05($address05){
        $this->address05 = $address05;
    }

    public function setAddress06($address06){
        $this->address06 = $address06;
    }

    public function setTel($tel){
        $this->tel = $tel;   
    }

    public function setDelFlag($delFlag){
        $this->delFlag = $delFlag;   
    }

    public function setDeliveryInsertDate($deliveryInsertDate){
        $this->deliveryInsertDate = $deliveryInsertDate;
    }

    public function setDeliveryUpdatedDate($deliveryUpdatedDate){
        $this->deliveryUpdatedDate = $deliveryUpdatedDate;   
    }
    
    public function getDeliveryId(){
        return $this->deliveryId;
    }

    public function getHashPassword(){
        return $this->lastName;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function getRubyLastName(){
        return $this->rubyLastName;
    }

    public function getRubyFirstName(){
        return $this->rubyFirstName;
    }
    
    public function getFullName(){
        return $this->lastName.$this->firstName;
    }

    public function getAddress01(){
        return $this->address01;
    }

    public function getAddress02(){
        return $this->address02;
    }

    public function getAddress03(){
        return $this->address03;
    }

    public function getAddress04(){
        return $this->address04;
    }

    public function getAddress05(){
        return $this->address05;
    }

    public function getAddress06(){
        return $this->address06;
    }
    
    public function getPost(){
        return $this->address01."-".$this->address02;
    }
    
    public function getAddress(){
        return $this->address03.$this->address04.$this->address05.$this->address06;
    }

    public function getTel(){
        return $this->tel;   
    }
        
    public function getDelFlag(){
        return $this->delFlag;   
    }

    public function getDeliveryInsertDate(){
        return $this->deliveryInsertDate;   
    }

    public function getDeliveryUpdatedDate(){
        return $this->deliveryUpdatedDate;   
    }   
    
    
}


?>