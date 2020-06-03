<?php
namespace Models;

class OrderDetailDto extends \Models\Model{
    private $detailId;
    private $orderId;
    private $itemCode;
    private $itemQuantity;
    private $itemPrice;//税別単価
    private $itemTax;
    //itemsテーブルのカラム
    private $itemName;
    private $itemImage;

    
    public function getDetailId(){
        return $this->detailId;
    }
    
    public function getOrderId(){
        return $this->orderId;
    }
    
    public function getItemCode(){
        return $this->itemCode;   
    }
    
    public function getItemQuantity(){
        return $this->itemQuantity;   
    }

    //new
    public function getItemPrice(){
        return $this->itemPrice;
    }
    
    //new
    public function getItemTax(){
        return $this->itemTax;
    }
    
    //new
    public function getItemPriceWithTax(){
        return $this->itemPrice + $this->itemTax;
    }
    
    public function getItemName(){
        return $this->itemName;
    }
    
    public function getTotalAmount(){
        return $this->getItemPriceWithTax() * $this->itemQuantity;
    }
    
    public function getItemImage(){
        return $this->itemImage;
    }
    
    public function setDetailId($detailId){
        $this->detailId = detailId;
    }
    
    public function setOrderId($orderId){
        $this->orderId = $orderId;
    }
    
    public function setItemCode($itemCode){
        $this->itemCode = $itemCode;   
    }
    
    public function setItemQuantity($itemQuantity){
        $this->itemQuantity = $itemQuantity;   
    }
    
    public function setItemPrice($itemPrice){
        $this->itemPrice = $itemPrice;
    }
    
    public function setItemTax($itemTax){
        $this->itemTax = $itemTax;
    }
    
    public function setItemName($itemName){
        $this->itemName = $itemName;
    }
    
    public function setitemImage($itemImage){
        $this->itemImage = $itemImage;
    }
}

?>