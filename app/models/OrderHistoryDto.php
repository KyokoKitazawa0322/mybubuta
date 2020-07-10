<?php
namespace Models;

class OrderHistoryDto{
    private $orderId;
    private $customerId;
    private $totalAmount;
    private $totalQuantity;
    private $tax;
    private $postage;
    private $paymentTerm;
    private $deliveryName;
    private $deliveryPost;
    private $deliveryAddr;
    private $deliveryTel;
    private $purchaseDate;
    
    private $totalAmountByTerm;
    private $totalQuantityByTerm;


    public function setOrderId($orderId){
        $this->orderId = $orderId;
    }
    
    public function setCustomerId($customerId){
        $this->customerId = $customerId;
    }
    
    public function setTotalAmount($totalAmount){
        $this->totalAmount = $totalAmount;    
    }
    
    public function setTotalQuantity($totalQuantity){
        $this->totalQuantity = $totalQuantity;
    }
    
    public function setTax($tax){
        $this->tax = $tax;   
    }
    
    public function setPostage($postage){
        $this->postage = $postage;   
    }
    
    public function setPaymentTerm($paymentTerm){
        $this->paymentTerm = $paymentTerm;   
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
    
    public function setTotalQuantityByTerm($totalQuantityByTerm){
        $this->totalQuantityByTerm = $totalQuantityByTerm;   
    }
    
    public function setTotalAmountByTerm($totalAmountByTerm){
        $this->totalAmountByTerm = $totalAmountByTerm;   
    }
    
    public function getOrderId(){
        return $this->orderId;
    }
    
    public function getCustomerId(){
        return $this->customerId;
    }
    
    public function getTotalAmount(){
        return $this->totalAmount;    
    }
    
    public function getTotalQuantity(){
        return $this->totalQuantity;
    }
    
    public function getTax(){
        return $this->tax;   
    }
    
    public function getPostage(){
        return $this->postage;   
    }
    
    public function getPaymentTerm(){
        return $this->paymentTerm;   
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
    
    public function getTotalQuantityByTerm(){
        return $this->totalQuantityByTerm;   
    }
    
    public function getTotalAmountByTerm(){
        return $this->totalAmountByTerm;   
    }
    
    
}


?>