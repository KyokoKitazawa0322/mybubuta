<?php
namespace Models;
use \Models\ItemsDao;
use \Config\Config;

class ItemsDto{
    
    private $itemCode;
    private $itemName;
    private $itemPrice;
    private $tax;
    private $itemCategory;
    private $itemImage;
    private $itemDetail;
    private $itemStock;
    private $itemdelflag;
    private $itemInsertdate;

    //getter--------------------------------
    public function getItemCode(){
        return $this->itemCode;
    }
    
    public function getItemName(){
        return $this->itemName;
    }
    
    public function getitemPrice(){
        return $this->itemPrice;
    }
    
    public function getTax(){
        return $this->tax;   
    }
    
    public function getitemPriceWithTax(){
        return $this->itemPrice + $this->tax;   
    }
    
    public function getItemCategory(){
        return $this->itemCategory;
    }
    
    public function getitemImage(){
        return $this->itemImage;
    }
    
    public function getitemDetail(){
        return $this->itemDetail;
    }
    
    public function getitemStock(){
        return $this->itemStock;
    }
    
    public function getItemDelFlug(){
        return $this->itemdelflag;
    }
    
    public function getItemInsertDate(){
        return $this->iteminsertdate;
    }
    
    //setter--------------------------------
    public function setItemCode($itemCode){
    	$this->itemCode = $itemCode;
    }
    
    public function setItemName($itemName){
        $this->itemName = $itemName;
    }
    
    public function setitemPrice($itemPrice){
        $this->itemPrice = $itemPrice;
    }
    
    public function setTax($tax){
        $this->tax = $tax;
    }
    
    public function setItemCategory($itemCategory){
        $this->itemCategory = $itemCategory;
    }
    
    public function setitemImage($itemImage){
        $this->itemImage = $itemImage;
    }
    
    public function setitemDetail($itemDetail){
        $this->itemDetail = $itemDetail;
    }
    
    public function setitemStock($itemStock){
        $this->itemStock = $itemStock;
    }
    
    public function setItemDelFlug($itemDelFlag){
        $this->itemdelflag = $itemDelFlag;
    }
    
    public function setItemInsertDate($itemInsertDate){
        $this->itemInsertdate = $itemInsertDate;
    }
}



?>