<?php
namespace Models;

class OrderHistoryDto extends \Models\Model{
    private $orderId;
    private $customerId;
    private $totalPayment;
    private $totalAmount;
    private $tax;
    private $postage;
    private $payment;
    private $deliveryName;
    private $deliveryPost;
    private $deliveryAddr;
    private $deliveryTel;
    private $purchaseDate;


    public function setOrderId($orderId){
        $this->orderId = $orderId;
    }
    
    public function setCustomerId($customerId){
        $this->customerId = $customerId;
    }
    
    public function setTotalPayment($totalPayment){
        $this->totalPayment = $totalPayment;    
    }
    
    public function setTotalAmount($totalAmount){
        $this->totalAmount = $totalAmount;
    }
    
    public function setTax($tax){
        $this->tax = $tax;   
    }
    
    public function setPostage($postage){
        $this->postage = $postage;   
    }
    
    public function setPayment($payment){
        $this->payment = $payment;   
    }
    
    public function setDeliveryName($deliveryName){
        $this->deliveryName = $deliveryName;   
    }
    
    public function setDeliveryPost($deliveryPost){
        $this->deliveryPost = $deliveryPost;   
    }

    public function setDeliveryAddr($deliveryAddr){
        $this->deliveryAddr = $deliveryAddr;   
    }
    
    public function setDeliveryTel($deliveryTel){
        $this->deliveryTel = $deliveryTel;   
    }
    
    public function setPurchaseDate($purchaseDate){
        $this->purchaseDate = $purchaseDate;   
    }
    
    public function getOrderId(){
        return $this->orderId;
    }
    
    public function getCustomerId(){
        return $this->customerId;
    }
    
    public function getTotalPayment(){
        return $this->totalPayment;    
    }
    
    public function getTotalAmount(){
        return $this->totalAmount;
    }
    
    public function getTax(){
        return $this->tax;   
    }
    
    public function getPostage(){
        return $this->postage;   
    }
    
    public function getPayment(){
        return $this->payment;   
    }
    
    public function getDeliveryName(){
        return $this->deliveryName;   
    }
    
    public function getDeliveryPost(){
        return $this->deliveryPost;   
    }

    public function getDeliveryAddr(){
        return $this->deliveryAddr;   
    }
    
    public function getDeliveryTel(){
        return $this->deliveryTel;   
    }
    
    public function getPurchaseDate(){
        return $this->purchaseDate;  
    }
    
    
    
}


?>