<?php
namespace Controllers;
use \Models\ItemsDao;
use \Models\ItemsDto;

class ItemDetailAction {
    
    private $item;
    
    public function execute() {
        $dao = new ItemsDao();
        $itemCode = $_GET['item_code'];
        
        try{
            $item = $dao->findItemByItemCode($itemCode);
            if($item){
                $this->item = $item;
            }else {
                header("Location:item_list.php");  
                exit();
            } 
        }catch(\PDOException $e){
            die('SQLエラー :'.$e->getMessage());
        }
    }
    
    public function getItem(){
        return $this->item;   
    }
}
    
?>