<?php
namespace Models;

class CustomerDto extends \Models\Model {

    private $customerId;
    private $hashPassword;
    private $lastName;
    private $firstName;
    private $rubyLastName;
    private $rubyFirstName;
    private $zipCode01;
    private $zipCode02;
    private $prefecture;
    private $city;
    private $blockNumber;
    private $buildingName;
    private $tel;
    private $mail;
    private $deliveryFlag;
    private $customerInsertDate;
    private $customerUpdatedDate;
    
   public function setCustomerId($customerId){
        $this->customerId = $customerId;
    }

    public function setHashPassword($hashPassword){
        $this->hashPassword = $hashPassword;
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

    public function setZipCode01($zipCode01){
        $this->zipCode01 = $zipCode01;
    }

    public function setZipCode02($zipCode02){
    	$this->zipCode02 = $zipCode02;
    }

    public function setPrefecture($prefecture){
        $this->prefecture = $prefecture;
    }

    public function setCity($city){
        $this->city = $city;
    }

    public function setBlockNumber($blockNumber){
        $this->blockNumber = $blockNumber;
    }

    public function setBuildingName($buildingName){
        $this->buildingName = $buildingName;
    }

    public function setTel($tel){
        $this->tel = $tel;   
    }

    public function setMail($mail){
        $this->mail = $mail;   
    }

    public function setDeliveryFlag($deliveryFlag){
        $this->deliveryFlag = $deliveryFlag;   
    }

    public function setCustomerInsertDate($customerInsertDate){
        $this->customerInsertDate = $customerInsertDate;
    }

    public function setCustomerUpdatedDate($customerUpdatedDate){
        $this->customerUpdatedDate = $customerUpdatedDate;   
    }
    

    public function getCustomerId(){
        return $this->customerId;
    }

    public function getHashPassword(){
        return $this->hashPassword;
    }

    public function getLastName(){
        return $this->lastName;
    }

    public function getFirstName(){
        return $this->firstName;
    }

    public function getFullName(){
        return $this->lastName.$this->firstName;
    }
    
    public function getRubyLastName(){
        return $this->rubyLastName;
    }

    public function getRubyFirstName(){
        return $this->rubyFirstName;
    }

    public function getFullRubyName(){
        return $this->rubyLastName.$this->rubyFirstName;
    }
    
    public function getZipCode01(){
        return $this->zipCode01;
    }

    public function getZipCode02(){
         return $this->zipCode02;
    }
    
    public function getZipCode(){
         return $this->zipCode01."-".$this->zipCode02;
    }

    public function getPrefecture(){
        return $this->prefecture;
    }

    public function getCity(){
        return $this->city;
    }

    public function getBlockNumber(){
        return $this->blockNumber;
    }

    public function getBuildingName(){
        return $this->buildingName;
    }

    public function getPost(){
        return $this->zipCode01."-".$this->zipCode02;
    }
    
    public function getAddress(){
        return $this->prefecture.$this->city.$this->blockNumber.$this->buildingName;
    }
    
    public function getTel(){
        return $this->tel;   
    }

    public function getMail(){
        return $this->mail;   
    }

    public function getDeliveryFlag(){
        return $this->deliveryFlag;   
    }

    public function getCustomerInsertDate(){
        return $this->customerInsertDate;
    }

    public function getCustomerUpdatedDate(){
        return $this->customerUpdatedDate;   
    }
}

?>