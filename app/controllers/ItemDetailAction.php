<?php
namespace Controllers;
use \Models\ItemsDao;
use \Models\ItemsDto;
use \Config\Config;
use \Models\OriginalException;

class ItemDetailAction {
    
    private $item;
    
    public function execute() {
        
        $dao = new ItemsDao();
        $itemCode = filter_input(INPUT_GET, 'item_code');
        
        try{
            $item = $dao->findItemByItemCode($itemCode);
            if($item){
                $this->item = $item;
            }else {
                header("Location:item_list.php");  
                exit();
            }
        } catch(\PDOException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());;
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            die('エラー:データベースの処理に失敗しました。');
        }catch(OriginalException $e){
            Config::outputLog($e->getCode(), $e->getMessage(), $e->getTraceAsString());
            header('Content-Type: text/plain; charset=UTF-8', true, 400);
            die('エラー:'.$e->getMessage());
        }
    }
    
    public function getItem(){
        return $this->item;   
    }
    

}
    
?>