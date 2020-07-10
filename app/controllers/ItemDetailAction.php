<?php
namespace Controllers;

use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\Model;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Models\DBConnectionException;

class ItemDetailAction {
    
    private $item;
    
    public function execute() {
        
        $itemCode = filter_input(INPUT_GET, 'item_code');
        $_SESSION['add_cart'] = 'undone';
        
        try{
            $model = Model::getInstance();
            $pdo = $model->getPdo();
            $itemsdao = new ItemsDao($pdo);   
            $item = $itemsdao->getItemByItemCodeForDetail($itemCode);
            $this->item = $item;
            
        }catch(DBConnectionException $e){
            $e->handler($e);   
            
        }catch(MyPDOException $e){
            $e->hadler($e);
            
        }catch(DBParamException $e){
            $e->handler($e);
        }
    }
    
    public function getItem(){
        return $this->item;   
    }
}
    
?>