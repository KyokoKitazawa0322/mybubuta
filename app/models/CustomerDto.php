<?php
namespace Models;

class CustomerDto extends \Models\Model {

    private $customerId;
    private $hashPassword;
    private $lastName;
    private $first_name;
    private $rubyLastName;
    private $rubyFirstName;
    private $address01;
    private $address02;
    private $address03;
    private $address04;
    private $address05;
    private $address06;
    private $tel;
    private $mail;
    private $delFlag;
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

    public function setFirstName($first_name){
        $this->first_name = $first_name;
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

    public function setMail($mail){
        $this->mail = $mail;   
    }

    public function setDelFlag($delFlag){
        $this->delFlag = $delFlag;   
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
        return $this->first_name;
    }

    public function getFullName(){
        return $this->lastName.$this->first_name;
    }
    
    public function getRubyLastName(){
        return $this->rubyLastName;
    }

    public function getRubyFirstName(){
        return $this->rubyFirstName;
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

    public function getMail(){
        return $this->mail;   
    }

    public function getDelFlag(){
        return $this->delFlag;   
    }

    public function getCustomerInsertDate(){
        return $this->customerInsertDate;
    }

    public function getCustomerUpdatedDate(){
        return $this->customerUpdatedDate;   
    }
}

?>