<?php
namespace Models;
use \Models\DeliveryDto;
use \Models\OriginalException;
use \Config\Config;
    
class DeliveryDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    
    /**
     * DeliveryDtoにSQL取得値をセット
     * @param Array $res　SQL取得結果
     * @return DeliveryDto
     * 例外処理は呼び出し元のメソッドで実施
     */
    public function setDto($res){
        
        $dto = new DeliveryDto();
        
        $dto->setDeliveryId($res['delivery_id']);
        $dto->setLastName($res['last_name']);
        $dto->setFirstName($res['first_name']);
        $dto->setRubyLastName($res['ruby_last_name']); 
        $dto->setRubyFirstName($res['ruby_first_name']); 
        $dto->setZipCode01($res['zip_code_01']); 
        $dto->setZipCode02($res['zip_code_02']); 
        $dto->setPrefecture($res['prefecture']); 
        $dto->setCity($res['city']); 
        $dto->setBlockNumber($res['block_number']); 
        $dto->setBuildingName($res['building_name']); 
        $dto->setTel($res['tel']);
        $dto->setdeliveryFlag($res['delivery_flag']);
        
        return $dto;
    }
    
    /**
     * 配送先住所の登録
     * @param string $last_name　入力されたユーザーの名字
     * @param string $first_name　入力されたユーザーの名前
     * @param string $last_name　入力されたユーザーの名字(カナ)
     * @param string $first_name　入力されたユーザーの名前(カナ)
     * @param string $zipCode01　入力されたユーザーの住所_郵便番号(3ケタ)
     * @param string $zipCode02　入力されたユーザーの住所_郵便番号(4ケタ)
     * @param string $prefecture　入力されたユーザーの住所_都道府県
     * @param string $city　入力されたユーザーの住所_市区町村等
     * @param string $blockNumber　入力されたユーザーの住所_番地等
     * @param string $buildingName　入力されたユーザーの住所_建物名等
     * @param string $tel　入力されたユーザーの電話番号
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(登録失敗時:code400)
     */
    public function insertDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $customerId){
        
        $dateTime = Config::getDateTime();
        $deliveryFlag = FALSE;
        
        try{
            $sql ="INSERT INTO delivery(last_name, first_name, ruby_last_name, ruby_first_name, zip_code_01, zip_code_02, prefecture, city, block_number, building_name, tel, customer_id, delivery_flag, delivery_insert_date)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $lastName, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $firstName, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $rubyLastName, \PDO::PARAM_STR);
            $stmt->bindvalue(4, $rubyFirstName, \PDO::PARAM_STR);
            $stmt->bindvalue(5, $zipCode01, \PDO::PARAM_STR);
            $stmt->bindvalue(6, $zipCode02, \PDO::PARAM_STR);
            $stmt->bindvalue(7, $prefecture, \PDO::PARAM_STR);
            $stmt->bindvalue(8, $city, \PDO::PARAM_STR);
            $stmt->bindvalue(9, $blockNumber, \PDO::PARAM_STR);
            $stmt->bindvalue(10, $buildingName, \PDO::PARAM_STR);
            $stmt->bindvalue(11, $tel, \PDO::PARAM_STR);
            $stmt->bindvalue(12, $customerId);
            $stmt->bindvalue(13, $deliveryFlag);
            $stmt->bindvalue(14, $dateTime, \PDO::PARAM_STR);
            $stmt->execute();
            
            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('更新に失敗しました。',222);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 配送先住所の更新
     * @param string $last_name　入力されたユーザーの名字
     * @param string $first_name　入力されたユーザーの名前
     * @param string $last_name　入力されたユーザーの名字(カナ)
     * @param string $first_name　入力されたユーザーの名前(カナ)
     * @param string $zipCode01　入力されたユーザーの住所_郵便番号(3ケタ)
     * @param string $zipCode02　入力されたユーザーの住所_郵便番号(4ケタ)
     * @param string $prefecture　入力されたユーザーの住所_都道府県
     * @param string $city　入力されたユーザーの住所_市区町村等
     * @param string $blockNumber　入力されたユーザーの住所_番地等
     * @param string $buildingName　入力されたユーザーの住所_建物名等
     * @param string $tel　入力されたユーザーの電話番号
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @param int $deliveryId　登録時に自動生成される配送先住所ID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時：code200)
     */
    public function updateDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $customerId, $deliveryId){

        $dateTime = Config::getDateTime();
        
        try{
            $sql ="UPDATE delivery SET last_name=?, first_name=?, ruby_last_name=?, ruby_first_name=?, zip_code_01=?, zip_code_02=?, prefecture=?, city=?, block_number=?, building_name=?, tel=?, delivery_updated_date=? where customer_id=? && delivery_id=?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $lastName, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $firstName, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $rubyLastName, \PDO::PARAM_STR);
            $stmt->bindvalue(4, $rubyFirstName, \PDO::PARAM_STR);
            $stmt->bindvalue(5, $zipCode01, \PDO::PARAM_STR);
            $stmt->bindvalue(6, $zipCode02, \PDO::PARAM_STR);
            $stmt->bindvalue(7, $prefecture, \PDO::PARAM_STR);
            $stmt->bindvalue(8, $city, \PDO::PARAM_STR);
            $stmt->bindvalue(9, $blockNumber, \PDO::PARAM_STR);
            $stmt->bindvalue(10, $buildingName, \PDO::PARAM_STR);
            $stmt->bindvalue(11, $tel, \PDO::PARAM_STR);
            $stmt->bindvalue(12, $dateTime, \PDO::PARAM_STR);
            $stmt->bindvalue(13, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(14, $deliveryId, \PDO::PARAM_INT);
            $stmt->execute();
            
            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('更新に失敗しました。',222);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 配送先住所の中にいつもの配送先住所があれば解除(delivery_flagを'0'→'1'に)
     * $customerIdをキーに更新
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時:code200)
     */
    public function releaseDeliveryDefault($customerId){  
        try{
            $deliveryDto = $this->getDefDeliveryInfo($customerId);
            if($deliveryDto){
                $deliveryFlag = 1;
                $deliveryId = $deliveryDto->getDeliveryId();
                $sql ="UPDATE delivery SET delivery_flag=? where customer_id=? && delivery_id=?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindvalue(1, $deliveryFlag, \PDO::PARAM_INT);
                $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
                $stmt->bindvalue(3, $deliveryId, \PDO::PARAM_INT);
                $stmt->execute();

                $count = $stmt->rowCount();
                if($count<1){
                    throw new OriginalException('更新に失敗しました。',222);
                }
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 配送先住所をいつもの配送先住所に更新(delivery_flagを'1'→'0'に)
     * $customerIdをキーに更新
     * 既にいつもの配送先住所に設定されている場合は更新なし
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時:code200)
     */
    public function setDeliveryDefault($customerId, $deliveryId){

        try{
            $deliveryDto = $this->getDeliveryInfoById($customerId, $deliveryId);
            if(!$deliveryDto->getDeliveryFlag()){
                $deliveryFlag = TRUE;
                $sql ="UPDATE delivery SET delivery_flag=? where customer_id=? && delivery_id=?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindvalue(1, $deliveryFlag, \PDO::PARAM_INT);
                $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
                $stmt->bindvalue(3, $deliveryId, \PDO::PARAM_INT);
                $stmt->execute();

                $count = $stmt->rowCount();
                if($count<1){
                    throw new OriginalException('更新に失敗しました。',222);
                }
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }

    /**
     * 配送先住所削除(退会時)
     * $deliveryIdと$customerIdをキーに配送先住所を削除
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @param int $deliveryId　登録時に自動生成される配送先住所ID
     * @throws PDOException 
     * @throws OriginalException(削除失敗時:code300)
     */
    public function deleteDeliveryInfo($customerId, $deliveryId){
        $sql = "DELETE FROM delivery WHERE customer_id = ? && delivery_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
        $stmt->bindvalue(2, $deliveryId, \PDO::PARAM_INT);
        $stmt->execute();
    }
    
    /**
     * 配送先住所情報取得
     * $cutomerIdをキーに全配送先住所情報を取得する。
     * なければfalseを返す
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @return DeliveryDto[]
     * @throws PDOException 
     */
    public function getDeliveryInfo($customerId){

        try{
            $sql = "SELECT * FROM delivery WHERE customer_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId);
            $stmt->execute();
            $res = $stmt->fetchAll();

            $deliveries = [];
            if($res){
                foreach($res as $row){
                    $dto = $this->setDto($row);
                    $deliveries[] = $dto;
                }
                return $deliveries;
            }else{
                return false;
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * いつもの配送先住所情報取得
     *　なければfalseを返す
     * $customerIdとdelivery_flagをキーに配送先住所情報を取得する。
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @return DeliveryDto
     * @throws PDOException 
     */
    public function getDefDeliveryInfo($customerId){
        $deliveryFlag = TRUE;
        try{
            $sql = "SELECT * FROM delivery WHERE customer_id = ? && delivery_flag =?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $deliveryFlag, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;      
            }else{
                return false;
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 配送先住所情報取得
     * $customerIdと$deliveryIdをキーに配送先住所情報を取得する。
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @param int $deliveryId　登録時に自動生成される配送先住所ID
     * @return DeliveryDto
     * @throws PDOException 
     * @throws OriginalException(取得失敗時:code100) 
     */
    public function getDeliveryInfoById($customerId, $deliveryId){
        
        try{
            $sql = "SELECT * FROM delivery WHERE customer_id = ? && delivery_id =?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $deliveryId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;
            }else{
                throw new OriginalException('取得に失敗しました。',111);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
}

?>