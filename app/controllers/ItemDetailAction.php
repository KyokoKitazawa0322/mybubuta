<?php
namespace Controllers;
use \Models\ItemsDao;
use \Models\ItemsDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
use \Config\Config;

class ItemDetailAction {
    
    private $item;
    
    public function execute() {
        
        $dao = new ItemsDao();
        $itemCode = filter_input(INPUT_GET, 'item_code');
        
        $_SESSION['add_cart'] = 'undone';
        
        try{
            $item = $dao->getItemByItemCodeForDetail($itemCode);
            $this->item = $item;
            
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