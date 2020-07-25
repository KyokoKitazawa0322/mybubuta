<?php
namespace Controllers;

use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\InvalidParamException;
use \Models\DBConnectionException;

class ItemDetailAction {
    
    private $item;
    private $itemStock;
    
    public function execute() {
        
        $cmd = Config::getPOST('cmd');
        $itemCode = Config::getGET('item_code');
        
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $itemsdao = new ItemsDao($pdo);   
            $item = $itemsdao->getItemByItemCodeForDetail($itemCode);
            $this->item = $item;
            $this->itemStock = $item->getItemStock();
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
            
        }catch(MyPDOException $e){
            $e->hadler($e);
            
        }catch(DBParamException $e){
            $e->handler($e);
        }
    
        if($cmd == "add_cart"){
            $itemQuantity = Config::getPOST('item_quantity');
            
            try{
                $this->checkItemQuantity($itemQuantity);
                
                $_SESSION['add_cart'] = array(
                    "item_code" => $itemCode,
                    "item_quantity" => $itemQuantity
                );      
            } catch(InvalidParamException $e){
                $e->handler($e);
            }
            header('Location:/html/cart.php');
            exit();
        }
    }
    
    public function getItem(){
        return $this->item;   
    }
    
    public function alertStock(){
        $itemStock = $this->itemStock;
        if($itemStock <= 10){
            return true;   
        }else{
            return false;
        }   
    }
    
    public function checkItemQuantity($itemQuantity){
        $itemStock = $this->itemStock;
        if($itemQuantity>$itemStock){
            throw new InvalidParamException('Invalid param for add_cart: $_POST["item_quantity"]='.$itemQuantity." > item_stock=".$itemStock);   
        } 
    }
}
    
?>