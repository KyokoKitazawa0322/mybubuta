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
    private $itemImageName;
    private $itemImagePath;
    private $itemDetail;
    private $itemStock;
    private $itemStatus;
    private $deleteFlag;
    private $itemInsertDate;
    private $itemUpdatedDate;
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
    
    public function getItemImageName(){
        return $this->itemImageName;
    }
    
    public function getItemImagePath(){
        return $this->itemImagePath;
    }
    
    public function getItemDetail(){
        return $this->itemDetail;
    }
    
    public function getItemStock(){
        return $this->itemStock;
    }
    
    public function getItemStatus(){
        return $this->itemStatus;
    }
    
//ステータス(1:販売中(表示有/購入可),2:入荷待ち(表示有/購入不可),3:販売終了(非表示/購入不可),4:一時掲載停止(非表示/購入不可),5:在庫切れ(表示有、購入不可),6:販売前待機中(非表示))
    public function getItemStatusAsString(){
        switch($this->itemStatus){
            case "1":
                return "販売中";
                break;
            case "2":
                return "入荷待ち";
                break;
            case "3":
                return "販売終了";
                break;
            case "4":
                return "一時掲載停止";
                break;
            case "5":
                return "在庫切れ";
                break;
            case "6":
                return "販売前待機中";
                break;
        }
    }
    public function getDeleteFlag(){
        return $this->deleteFlag;
    }
    
    public function getItemInsertDate(){
        return $this->itemInsertDate;
    }
    
    public function getItemUpdatedDate(){
        if($this->itemUpdatedDate == '0000-00-00 00:00:00'){
            return false;
        }else{
            return $this->itemUpdatedDate;
        }
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
    
    public function setItemImageName($itemImageName){
        $this->itemImageName = $itemImageName;
    }
    
    public function setItemImagePath($itemImagePath){
        $this->itemImagePath = $itemImagePath;
    }
    
    public function setItemDetail($itemDetail){
        $this->itemDetail = $itemDetail;
    }
    
    public function setItemStock($itemStock){
        $this->itemStock = $itemStock;
    }
    
    public function setItemStatus($itemStatus){
        $this->itemStatus = $itemStatus;
    }
    
    public function setDeleteFlug($deleteFlag){
        $this->deleteFlag = $deleteFlag;
    }
    
    public function setItemInsertDate($itemInsertDate){
        $this->itemInsertDate = $itemInsertDate;
    }
    
    public function setItemUpdatedDate($itemUpdatedDate){
        $this->itemUpdatedDate = $itemUpdatedDate;
    }
    
    public function setItemSales($itemSales){
        $this->itemSales = $itemSales;   
    }
}



?>