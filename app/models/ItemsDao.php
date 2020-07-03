<?php
namespace Models;

use \Models\ItemsDto;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;

class ItemsDao extends \Models\Model { 

    public function __construct(){
        parent::__construct();
    }
    
    /**
     * select文共通処理
     * @return ItemsDto[] | boolean FALSE
     * 例外処理は呼び出し元で行う
     */
    public function select($sql){
        $stmt = $this->pdo->query($sql); 
        $res = $stmt->fetchAll();
        if($res){
            $items = [];
            foreach($res as $row) {
                $dto = $this->setDto($row);
                $items[] = $dto;
            }
            return $items;
        }else{
            return FALSE;   
        }
    }
    
    /**
     * データ取得結果をItemsDtoクラスへ格納する共通処理
     * @return ItemsDto[]
     */    
    public function setDto($res){

        $dto = new ItemsDto();
        
        $dto->setItemCode($res['item_code']);
        $dto->setItemName($res['item_name']);
        $dto->setItemPrice($res['item_price']);
        $dto->setItemTax($res['item_tax']);
        $dto->setItemCategory($res['item_category']);
        $dto->setItemImageName($res['item_image_name']);
        $dto->setItemImagePath($res['item_image_path']);
        $dto->setItemDetail($res['item_detail']); 
        $dto->setItemStock($res['item_stock']);
        $dto->setItemSales($res['item_sales']); 
        $dto->setItemStatus($res['item_status']); 
        $dto->setItemInsertDate($res['item_insert_date']); 
        $dto->setItemUpdatedDate($res['item_updated_date']); 
        
        return $dto;
    }
    
    /**
     * 商品詳細を取得
     * $itemCodeをキーに商品情報を取得する。
     * @param string $itemCode 商品コード
     * @return ItemsDto
     * @throws MyPDOException
     * @throws DBParamException
     */
    public function getItemByItemCode($itemCode){
        try{
            $dto = new ItemsDto();
            $sql = "SELECT * FROM items WHERE item_code=? && delete_flag = FALSE ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
            $stmt->execute();   
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;
            }else{
                $result=preg_replace("/item_code=\?/", 'item_code='.$itemCode, $sql);
                throw new DBParamException("invalid param error:".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 商品詳細を取得
     * $itemCodeをキーに商品情報を取得する
     * item_statusが1(販売中),2(入荷待ち),5(在庫切れ)の商品のみ取得可能。
     * @param string $itemCode 商品コード
     * @return ItemsDto
     * @throws MyPDOException
     * @throws DBParamException
     */
    public function getItemByItemCodeForDetail($itemCode){
        try{
            $dto = new ItemsDto();
            $sql = "SELECT * FROM items WHERE item_code=? && delete_flag = FALSE AND item_status IN(1,2,5)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
            $stmt->execute();   
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;
            }else{
                $result=preg_replace("/item_code=\?/", 'item_code='.$itemCode, $sql);
                throw new DBParamException("invalid param error:".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 商品詳細を取得
     * $itemCodeをキーに商品情報を取得。
     * item_statusが1(販売中)の商品のみ取得可能。
     * @param string $itemCode 商品コード
     * @return ItemsDto
     * @throws MyPDOException
     * @throws DBParamException
     */
    public function getItemByItemCodeForPurchase($itemCode){
        try{
            $dto = new ItemsDto();
            $sql = "SELECT * FROM items WHERE item_code=? && delete_flag = FALSE AND item_status = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
            $stmt->execute();   
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;
            }else{
                $result=preg_replace("/item_code=\?/", 'item_code='.$itemCode, $sql);
                throw new DBParamException("invalid param error:".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
        
    /**
     * 商品検索
     * @param string $categories カテゴリー：値があればSQL文を加えbindvalueにセット
     * @param string $keyWord キーワード：値があればSQL文を加えbindvalueにセット
     * @param int $minPrice 下限価格：値があればSQL文を加えbindvalueにセット
     * @param int $maxPrice 上限価格：値があればSQL文を加えbindvalueにセット
     * @param string $sortKey 並び替え条件：番号によって並び替え箇所のSQL文を振り分ける
     * @return ItemsDto[] | boolean FALSE
     * @throws MyPDOException
     */
    public function findItems($categories, $keyWord, $minPrice, $maxPrice, $sortKey){
        try{
            $sql = "SELECT a.item_name, a.item_code, a.item_price, a.item_tax, a.item_category, a.item_image_name, a.item_image_path, a.item_detail, a.item_sales, a.item_stock, a.item_status, a.item_insert_date, a.item_updated_date FROM items a LEFT OUTER JOIN order_detail b ON a.item_code=b.item_code LEFT OUTER JOIN order_history c ON c.order_id=b.order_id WHERE item_status IN ('1', '2', '5') AND delete_flag=FALSE";
            
            if(!empty($categories)){
                $category = "";
                for($i=0; $i<count($categories); $i++){
                    $category = $category.':'.$i.',';
                }
                $category = preg_replace("/,$/", "", $category);
                $sql = $sql." AND a.item_category IN ({$category})";
            }
            
            if(!empty($keyWord)){
                $sql = $sql." AND a.item_name LIKE :keyword";
            }        
            if(!empty($minPrice)){
                $sql = $sql." AND a.item_price+a.item_tax>=:minprice";
            }
            if(!empty($maxPrice)){
                $sql = $sql." AND a.item_price+a.item_tax<=:maxprice";
            }
            if($sortKey == "01"){
                $sql = $sql." GROUP BY a.item_code ORDER BY a.item_price ASC";
            }elseif($sortKey == "02"){
                $sql = $sql." GROUP BY a.item_code ORDER BY a.item_price DESC";
            }elseif($sortKey == "03"){
                $sql = $sql." GROUP BY a.item_code ORDER BY SUM(CASE WHEN date_format(c.purchase_date, '%Y-%m-%d') BETWEEN DATE_SUB(curdate(), interval 7 day) AND DATE_ADD(curdate(), interval 0 day) THEN b.item_quantity ELSE 0 END) DESC";
            }else{     
                $sql = $sql." GROUP BY a.item_code ORDER BY a.item_insert_date DESC";
            }
    
            $stmt = $this->pdo->prepare($sql);

            if(!empty($categories)){
                $i=0;
                foreach($categories as $category){
                    $stmt->bindvalue(":{$i}", $category, \PDO::PARAM_STR);
                    $i++; 
                    //IN (:skirt, :tops, :dress)-> 「''」がはいるとエスケープされるためバラバラに定義
                }
            }
            if(!empty($keyWord)){
                $stmt->bindvalue(":keyword", '%'.$keyWord.'%', \PDO::PARAM_STR);
            }
            if(!empty($minPrice)){
                $stmt->bindvalue(":minprice", $minPrice, \PDO::PARAM_INT);
            }
            if(!empty($maxPrice)){
                $stmt->bindvalue(":maxprice", $maxPrice, \PDO::PARAM_INT);
            }
            $stmt->execute();
            $res = $stmt->fetchAll();

            if($res){
                $items = [];
                foreach($res as $row) {
                    $dto = $this->setDto($row);
                    $items[] = $dto;
                }
                return $items;
            }else {
                return FALSE;   
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 現在日時の現在の日付～1週間前までの売上上位5点のアイテム情報取得
     * @return itemsDto[] | FALSE
     * @throws MyPDOException
     * @throws NoRecordException
     */
    public function getItemsInfoRankByWeek(){
     
        try{
            $sql = "SELECT a.item_name, a.item_code, a.item_price, a.item_tax, a.item_category, a.item_image_name, a.item_image_path, a.item_detail, a.item_sales, a.item_status, a.item_stock, a.item_insert_date, a.item_updated_date FROM items a LEFT OUTER JOIN order_detail b ON a.item_code = b.item_code LEFT OUTER JOIN order_history c ON c.order_id = b.order_id WHERE a.delete_flag = FALSE AND a.item_status IN(1,2,5) GROUP BY a.item_code ORDER BY SUM(CASE WHEN date_format(c.purchase_date, '%Y-%m-%d') BETWEEN DATE_SUB(curdate(), interval 7 day) AND DATE_ADD(curdate(), interval 0 day) THEN b.item_quantity ELSE 0 END) DESC LIMIT 5";
            
            $stmt = $this->pdo->query($sql);
            $stmt->execute();
            $res = $stmt->fetchAll();

            if($res){
                $items = [];
                foreach($res as $row) {
                    $dto = $this->setDto($row);
                    $items[] = $dto;
                }
                return $items;
            }else {
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * 商品の販売数と在庫数を更新
     * $itemCodeをキーにitem_salesに$itemQuantityを加算、item_stockに$itemQuantityを減算
     * $item_stockが0になった場合はitem_statusを1(在庫切れ)に変更
     * @throws MyPDOException
     * @throws DBParamException
     */
    public function recordItemSales($itemQuantity, $itemCode){
        try{
            $sql ="UPDATE items SET item_sales = item_sales+?, item_stock = item_stock-? WHERE item_code=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemQuantity, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $itemQuantity, \PDO::PARAM_INT);
            $stmt->bindvalue(3, $itemCode, \PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                
                $pattern=array("/item_sales+\?/", "/item_stock-\?/", "/item_code=\?/");
                $replace=array('item_sales+'.$itemQuantity, 'item_stock-'.$itemQuantity, 'item_code='.$itemCode);
                
                $result=preg_replace($pattern, $replace, $sql);
                throw new DBParamException("invalid param error:".$result);
            }
            
            $itemDto = $this->getItemByItemCode($itemCode);
            if($itemDto->getItemStock()=="0"){
                $itemStatus="5";//在庫切れ
                $this->updateItemStatus($itemStatus, $itemCode);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        
        }catch(DBParamException $e){
            throw $e;
        }
    }
    
   /**
     * 商品検索(管理ページ用)
     * @param string $category カテゴリー：値があればSQL文を加えbindvalueにセット
     * @param string $keyWord キーワード：値があればSQL文を加えbindvalueにセット
     * @param int $minPrice 下限価格：値があればSQL文を加えbindvalueにセット
     * @param int $maxPrice 上限価格：値があればSQL文を加えbindvalueにセット
     * @param string $sortKey 並び替え条件：番号によって並び替え箇所のSQL文を振り分ける
     * @param string $itemCode 商品コード(管理ページ用)
     * @param string $itemStatus ステータス(管理ページ用)
     * @return ItemsDto[] | boolean FALSE
     * @throws MyPDOException
     */
    public function findItemsForAdmin($category, $keyWord, $minPrice, $maxPrice, $sortkey, $itemCode, $status){
        try{
            $sql = "SELECT * FROM items WHERE delete_flag=FALSE";
            
            if(!empty($category)){
                $sql = $sql." AND item_category=:category";
            }
            if(!empty($keyWord)){
                $sql = $sql." AND item_name LIKE :keyword";
            }        
            if(!empty($minPrice)){
                $sql = $sql." AND item_price+item_tax>=:minprice";
            }
            if(!empty($maxPrice)){
                $sql = $sql." AND item_price+item_tax<=:maxprice";
            }
            if(!empty($itemCode)){
                $sql = $sql." AND item_code=:itemcode";
            }
            if(!empty($status)){
                $sql = $sql." AND item_status=:status";   
            }
            switch($sortkey){
                case "item_price_asc":
                    $sql = $sql." ORDER BY item_price ASC";
                    break;
                case "item_stock_asc":
                    $sql = $sql." ORDER BY item_stock ASC";
                    break;
                case "item_sales_asc":
                    $sql = $sql." ORDER BY item_sales ASC";
                    break;
                case "item_insert_date_asc":
                    $sql = $sql." ORDER BY item_insert_date ASC";
                    break;
                case "item_price_desc":
                    $sql = $sql." ORDER BY item_price DESC";
                    break;
                case "item_stock_desc":
                    $sql = $sql." ORDER BY item_stock DESC";
                    break;
                case "item_sales_desc":
                    $sql = $sql." ORDER BY item_sales DESC";
                    break;
                case "item_insert_date_desc":
                    $sql = $sql." ORDER BY item_insert_date DESC";
                    break;                    
            }
    
            $stmt = $this->pdo->prepare($sql);

            if(!empty($category)){
                $stmt->bindvalue(":category", $category, \PDO::PARAM_STR);
            }
            if(!empty($keyWord)){
                $stmt->bindvalue(":keyword", '%'.$keyWord.'%', \PDO::PARAM_STR);
            }
            if(!empty($minPrice)){
                $stmt->bindvalue(":minprice", $minPrice, \PDO::PARAM_INT);
            }
            if(!empty($maxPrice)){
                $stmt->bindvalue(":maxprice", $maxPrice, \PDO::PARAM_INT);
            }
            if(!empty($itemCode)){
                $stmt->bindvalue(":itemcode", $itemCode, \PDO::PARAM_STR);
            }
            if(!empty($status)){
                $stmt->bindvalue(":status", $status, \PDO::PARAM_STR);
            }
            $stmt->execute();
            $res = $stmt->fetchAll();

            if($res){
                $items = [];
                foreach($res as $row) {
                    $dto = $this->setDto($row);
                    $items[] = $dto;
                }
                return $items;
            }else {
                return FALSE;   
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 商品情報更新
     * @param string $itemName　商品名
     * @param string $itemCode 商品コード
     * @param int $itemPrice 商品価格(税抜き)
     * @param int $itemStock 在庫数
     * @param string $itemStatus 商品ステータス
     * @param string $itemDetail 商品説明
     * @param string $itemCode 既存の商品コード
     * @throws MyPDOException
     * @throws DBParamException
     */
    public function updateItemInfo($itemName, $updateItemCode, $itemPrice, $itemStock, $itemStatus, $itemDetail, $itemCode){
        
        $itemTax = $itemPrice * Config::TAXRATE;
        $itemUpdatedDate = Config::getDateTime();
        
        try{
            
            $sql ="UPDATE items SET item_name=?, item_code=?, item_price=?, item_tax=?, item_stock=?, item_status=?, item_detail=?, item_updated_date=? WHERE item_code=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemName, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $updateItemCode, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $itemPrice, \PDO::PARAM_INT);
            $stmt->bindvalue(4, $itemTax, \PDO::PARAM_INT);
            $stmt->bindvalue(5, $itemStock, \PDO::PARAM_INT);
            $stmt->bindvalue(6, $itemStatus, \PDO::PARAM_STR);
            $stmt->bindvalue(7, $itemDetail, \PDO::PARAM_STR);
            $stmt->bindvalue(8, $itemUpdatedDate, \PDO::PARAM_STR);
            $stmt->bindvalue(9, $itemCode, \PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                $pattern=array("/item_name=\?/", "/item_code=\?/", "/item_price=\?/", "/item_tax=\?/", "/item_stock=\?/", "/item_status=\?/","/item_detail=\?/",  "/item_updated_date=\?/", "/item_code=\?/");
                $replace=array('item_name='.$itemName, 'item_code='.$updateItemCode, 'item_price='.$itemPrice, 'item_tax='.$itemTax, 'item_stock='.$itemStock, 'item_status='.$itemStatus, 'item_detail='.$itemDetail, 'item_updated_date='.$itemUpdatedDate, 'item_code='.$itemCode);
                $result=preg_replace($pattern, $replace, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 商品画像更新
     * @param string $itemImageName　商品画像ファイル名($_FILEで取得)
     * @param string $itemImagePath　商品画像パス
     * @param string $itemCode 既存の商品コード
     * @throws MyPDOException
     * @throws DBParamException
     */
    public function updateItemImage($itemImageName, $itemImagePath, $itemCode){
        
        $itemUpdatedDate = Config::getDateTime();
        
        try{
            $sql ="UPDATE items SET item_image_name=?, item_image_path=?, item_updated_date=? WHERE item_code=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemImageName, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $itemImagePath, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $itemUpdatedDate, \PDO::PARAM_STR);
            $stmt->bindvalue(4, $itemCode, \PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                
                $pattern=array("/item_image_name=\?/", "/item_image_path=\?/", "/item_updated_date=\?/", "/item_code=\?/");
                $replace=array('item_image_name='.$itemImageName, 'item_image_path='.$itemImagePath, 'item_updated_date='.$itemUpdatedDate, 'item_code='.$itemCode);
                $result=preg_replace($pattern, $replace, $sql);
                
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 商品情報登録
     * @param string $itemCode 商品コード
     * @param string $itemName　商品名
     * @param int $itemPrice 商品価格(税抜き)
     * @param string $itemCategory 商品カテゴリー
     * @param string $itemImageName　商品画像ファイル名($_FILEで取得)
     * @param string $itemImagePath　商品画像パス
     * @param string $itemDetail 商品説明
     * @param int $itemStock 在庫数
     * @param string $itemStatus 商品ステータス
     * @throws MyPDOException
     */
    public function insertItemInfo($itemCode, $itemName, $itemPrice, $itemCategory, $itemImageName, $itemImagePath, $itemDetail, $itemStock, $itemStatus){
        
        $itemTax = $itemPrice * Config::TAXRATE;
        $deleteFlag = FALSE;
        $itemInsertDate = Config::getDateTime();
        $itemSales = 0;
        
        try{
            $sql = "INSERT INTO items (item_code, item_name, item_price, item_tax, item_category, item_image_name, item_image_path, item_detail, item_stock, item_status, delete_flag, item_insert_date, item_sales)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemCode, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $itemName, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $itemPrice, \PDO::PARAM_INT);
            $stmt->bindvalue(4, $itemTax, \PDO::PARAM_INT);
            $stmt->bindvalue(5, $itemCategory, \PDO::PARAM_STR);
            $stmt->bindvalue(6, $itemImageName, \PDO::PARAM_STR);
            $stmt->bindvalue(7, $itemImagePath, \PDO::PARAM_STR);
            $stmt->bindvalue(8, $itemDetail, \PDO::PARAM_STR);
            $stmt->bindvalue(9, $itemStock, \PDO::PARAM_INT);
            $stmt->bindvalue(10, $itemStatus, \PDO::PARAM_STR);
            $stmt->bindvalue(11, $deleteFlag, \PDO::PARAM_INT);
            $stmt->bindvalue(12, $itemInsertDate, \PDO::PARAM_STR);
            $stmt->bindvalue(13, $itemSales, \PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->rowCount();
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 商品削除(DBからは消さずにdelete_flagをTRUEに更新)
     * @param string $itemCode 商品コード
     * @throws MyPDOException
     * @throws DBParamException
     */
    public function deleteItem($itemCode){
        
        $itemUpdatedDate = Config::getDateTime();
        
        try{
            $sql ="UPDATE items SET delete_flag=TRUE, item_updated_date=? WHERE item_code=?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemUpdatedDate, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $itemCode, \PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                $pattern=array("/item_updated_date=\?/", "/item_code=\?/");
                $replace=array('item_updated_date='.$itemUpdatedDate, 'item_code='.$itemCode);
                $result=preg_replace($pattern, $replace, $sql);
                
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 商品ステータス更新
     * @param string $itemStatus 商品ステータス
     * @param string $itemCode 商品コード
     * @throws MyPDOException
     * @throws DBParamException
     */
    public function updateItemStatus($itemStatus, $itemCode){
        
        $itemUpdatedDate = Config::getDateTime();
        
        try{
            $sql ="UPDATE items SET item_status=?, item_updated_date=? WHERE item_code=?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $itemStatus, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $itemUpdatedDate, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $itemCode, \PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                $pattern=array("/item_status=\?/", "/item_updated_date=\?/", "/item_code=\?/");
                $replace=array('item_status='.$itemStatus, 'item_updated_date='.$itemUpdatedDate, 'item_code='.$itemCode);
                $result=preg_replace($pattern, $replace, $sql);
                
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
}
?>