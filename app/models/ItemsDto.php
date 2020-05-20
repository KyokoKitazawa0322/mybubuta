<?php
namespace Models;
use \Models\ItemsDao;
use \Config\Config;
//ACTIONクラス(use DAO;)　入力値の受けとりとメソッドの実行。DTOオブジェクトが返り値として得られる
//DAOクラス(use DTO;)　メソッドの定義。SQLで取得した値をDTOにsetterで渡し格納させる
//DTOクラス　getter/setterでデータ保持、DTOオブジェクトを返す
class ItemsDto{
    
    private $itemCode;
    private $itemName;
    private $itemPrice;
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
    
    public function getitemPriceWithTax(){
        return $this->itemPrice * Config::TAX;   
    }
    
    public function getitemTax(){
        return $this->itemPrice * Config::TAXRATE;   
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