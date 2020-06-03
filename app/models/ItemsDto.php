<?php
namespace Models;
use \Models\ItemsDao;
use \Config\Config;

class ItemsDto{
    
    private $itemCode;
    private $itemName;
    private $itemPrice;
    private $itemitemTax;
    private $itemCategory;
    private $itemImage;
    private $itemDetail;
    private $itemStock;
    private $deleteFlag;
    private $itemInsertdate;
    private $itemSales;

    //getter--------------------------------
    public function getItemCode(){
        return $this->itemCode;
    }
    
    public function getItemName(){
        return $this->itemName;
    }
    
    public function getItemPrice(){
        return $this->itemPrice;
    }
    
    public function getItemTax(){
        return $this->itemTax;   
    }
    
    public function getItemPriceWithTax(){
        return $this->itemPrice + $this->itemTax;   
    }
    
    public function getItemCategory(){
        return $this->itemCategory;
    }
    
    public function getItemImage(){
        return $this->itemImage;
    }
    
    public function getItemDetail(){
        return $this->itemDetail;
    }
    
    public function getItemStock(){
        return $this->itemStock;
    }
    
    public function getDeleteFlug(){
        return $this->deleteFlag;
    }
    
    public function getItemInsertDate(){
        return $this->iteminsertdate;
    }
    
    public function getItemSales(){
        return $this->itemSales;   
    }
    
    //setter--------------------------------
    public function setItemCode($itemCode){
    	$this->itemCode = $itemCode;
    }
    
    public function setItemName($itemName){
        $this->itemName = $itemName;
    }
    
    public function setItemPrice($itemPrice){
        $this->itemPrice = $itemPrice;
    }
    
    public function setItemTax($itemTax){
        $this->itemTax = $itemTax;
    }
    
    public function setItemCategory($itemCategory){
        $this->itemCategory = $itemCategory;
    }
    
    public function setItemImage($itemImage){
        $this->itemImage = $itemImage;
    }
    
    public function setItemDetail($itemDetail){
        $this->itemDetail = $itemDetail;
    }
    
    public function setItemStock($itemStock){
        $this->itemStock = $itemStock;
    }
    
    public function setDeleteFlug($deleteFlag){
        $this->deleteFlag = $deleteFlag;
    }
    
    public function setItemInsertDate($itemInsertDate){
        $this->itemInsertdate = $itemInsertDate;
    }
    
    public function setItemSales($itemSales){
        $this->itemSales = $itemSales;   
    }
}



?>