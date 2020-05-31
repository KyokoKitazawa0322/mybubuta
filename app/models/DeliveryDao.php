<?php
namespace Models;
use \Models\DeliveryDto;
use \Models\OriginalException;
    
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
        $dto->setAddress01($res['address_01']); 
        $dto->setAddress02($res['address_02']); 
        $dto->setAddress03($res['address_03']); 
        $dto->setAddress04($res['address_04']); 
        $dto->setAddress05($res['address_05']); 
        $dto->setAddress06($res['address_06']); 
        $dto->setTel($res['tel']);
        $dto->setDelFlag($res['del_flag']);
        
        return $dto;
    }
    
    /**
     * 配送先住所の登録
     * @param string $last_name　入力されたユーザーの名字
     * @param string $first_name　入力されたユーザーの名前
     * @param string $last_name　入力されたユーザーの名字(カナ)
     * @param string $first_name　入力されたユーザーの名前(カナ)
     * @param string $address01　入力されたユーザーの住所_郵便番号(3ケタ)
     * @param string $address02　入力されたユーザーの住所_郵便番号(4ケタ)
     * @param string $address03　入力されたユーザーの住所_都道府県
     * @param string $address04　入力されたユーザーの住所_市区町村等
     * @param string $address05　入力されたユーザーの住所_番地等
     * @param string $address06　入力されたユーザーの住所_建物名等
     * @param string $tel　入力されたユーザーの電話番号
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(登録失敗時:code400)
     */
    public function insertDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $customerId){
        
        try{
            $sql ="INSERT INTO delivery(last_name, first_name, ruby_last_name, ruby_first_name, address_01, address_02, address_03, address_04, address_05, address_06, tel, customer_id, del_flag, delivery_insert_date)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,now())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $lastName, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $firstName, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $rubyLastName, \PDO::PARAM_STR);
            $stmt->bindvalue(4, $rubyFirstName, \PDO::PARAM_STR);
            $stmt->bindvalue(5, $address01, \PDO::PARAM_STR);
            $stmt->bindvalue(6, $address02, \PDO::PARAM_STR);
            $stmt->bindvalue(7, $address03, \PDO::PARAM_STR);
            $stmt->bindvalue(8, $address04, \PDO::PARAM_STR);
            $stmt->bindvalue(9, $address05, \PDO::PARAM_STR);
            $stmt->bindvalue(10, $address06, \PDO::PARAM_STR);
            $stmt->bindvalue(11, $tel, \PDO::PARAM_STR);
            $stmt->bindvalue(12, $customerId);
            $stmt->bindvalue(13, "1");
            $result = $stmt->execute();
            
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
     * @param string $address01　入力されたユーザーの住所_郵便番号(3ケタ)
     * @param string $address02　入力されたユーザーの住所_郵便番号(4ケタ)
     * @param string $address03　入力されたユーザーの住所_都道府県
     * @param string $address04　入力されたユーザーの住所_市区町村等
     * @param string $address05　入力されたユーザーの住所_番地等
     * @param string $address06　入力されたユーザーの住所_建物名等
     * @param string $tel　入力されたユーザーの電話番号
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @param int $deliveryId　登録時に自動生成される配送先住所ID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時：code200)
     */
    public function updateDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $customerId, $deliveryId){

        try{
            $sql ="UPDATE delivery SET last_name=?, first_name=?, ruby_last_name=?, ruby_first_name=?, address_01=?, address_02=?, address_03=?, address_04=?, address_05=?, address_06=?, tel=?, delivery_updated_date = now() where customer_id=? && delivery_id=?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $lastName, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $firstName, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $rubyLastName, \PDO::PARAM_STR);
            $stmt->bindvalue(4, $rubyFirstName, \PDO::PARAM_STR);
            $stmt->bindvalue(5, $address01, \PDO::PARAM_STR);
            $stmt->bindvalue(6, $address02, \PDO::PARAM_STR);
            $stmt->bindvalue(7, $address03, \PDO::PARAM_STR);
            $stmt->bindvalue(8, $address04, \PDO::PARAM_STR);
            $stmt->bindvalue(9, $address05, \PDO::PARAM_STR);
            $stmt->bindvalue(10, $address06, \PDO::PARAM_STR);
            $stmt->bindvalue(11, $tel, \PDO::PARAM_STR);
            $stmt->bindvalue(12, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(13, $deliveryId, \PDO::PARAM_INT);
            $result = $stmt->execute();
            
            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('更新に失敗しました。',222);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 配送先住所の中にいつもの配送先住所があれば解除(del_flagを'0'→'1'に)
     * $customerIdをキーに更新
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時:code200)
     */
    public function releaseDeliveryDefault($customerId){  
        try{
            $deliveryDto = $this->getDefDeliveryInfo($customerId);
            if($deliveryDto){
                $delFlag = 1;
                $deliveryId = $deliveryDto->getDeliveryId();
                $sql ="UPDATE delivery SET del_flag=? where customer_id=? && delivery_id=?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindvalue(1, $delFlag, \PDO::PARAM_INT);
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
     * 配送先住所をいつもの配送先住所に更新(del_flagを'1'→'0'に)
     * $customerIdをキーに更新
     * 既にいつもの配送先住所に設定されている場合は更新なし
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時:code200)
     */
    public function setDeliveryDefault($customerId, $deliveryId){

        try{
            $deliveryDto = $this->getDeliveryInfoById($customerId, $deliveryId);
            if($deliveryDto->getDelFlag() == 1){
                $delFlag = 0;
                $sql ="UPDATE delivery SET del_flag=? where customer_id=? && delivery_id=?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindvalue(1, $delFlag, \PDO::PARAM_INT);
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
     * $customerIdとdel_flag(=0)をキーに配送先住所情報を取得する。
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @return DeliveryDto
     * @throws PDOException 
     */
    public function getDefDeliveryInfo($customerId){
        $delFlag = 0;
        try{
            $sql = "SELECT * FROM delivery WHERE customer_id = ? && del_flag =?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $delFlag, \PDO::PARAM_INT);
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