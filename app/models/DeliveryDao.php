<?php
namespace Models;
use \Models\DeliveryDto;
use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
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
        $dto->setDeliveryFlag($res['delivery_flag']);
        $dto->setDeliveryInsertDate($res['delivery_insert_date']);
        
        return $dto;
    }
    
    /**
     * 配送先住所の登録
     * @param string $last_name　ユーザーの名字
     * @param string $first_name　ユーザーの名前
     * @param string $last_name　ユーザーの名字(カナ)
     * @param string $first_name　ユーザーの名前(カナ)
     * @param string $zipCode01　ユーザーの住所_郵便番号(3ケタ)
     * @param string $zipCode02　ユーザーの住所_郵便番号(4ケタ)
     * @param string $prefecture　ユーザーの住所_都道府県
     * @param string $city　ユーザーの住所_市区町村等
     * @param string $blockNumber　ユーザーの住所_番地等
     * @param string $buildingName　ユーザーの住所_建物名等
     * @param string $tel　ユーザーの電話番号
     * @param int $customerId　カスタマーID
     * @throws MyPDOException 
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
            $stmt->bindvalue(12, $customerId, \PDO::PARAM_STR);
            $stmt->bindvalue(13, $deliveryFlag, \PDO::PARAM_INT);
            $stmt->bindvalue(14, $dateTime, \PDO::PARAM_STR);
            $stmt->execute();
            
            $count = $stmt->rowCount();
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 配送先住所の更新
     * @param string $last_name　ユーザーの名字
     * @param string $first_name　ユーザーの名前
     * @param string $last_name　ユーザーの名字(カナ)
     * @param string $first_name　ユーザーの名前(カナ)
     * @param string $zipCode01　ユーザーの住所_郵便番号(3ケタ)
     * @param string $zipCode02　ユーザーの住所_郵便番号(4ケタ)
     * @param string $prefecture　ユーザーの住所_都道府県
     * @param string $city　ユーザーの住所_市区町村等
     * @param string $blockNumber　ユーザーの住所_番地等
     * @param string $buildingName　ユーザーの住所_建物名等
     * @param string $tel　ユーザーの電話番号
     * @param int $customerId　カスタマーID
     * @param int $deliveryId　配送先住所ID
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function updateDeliveryInfo($lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $customerId, $deliveryId){

        $dateTime = Config::getDateTime();
        
        try{
            $sql ="UPDATE delivery SET last_name=?, first_name=?, ruby_last_name=?, ruby_first_name=?, zip_code_01=?, zip_code_02=?, prefecture=?, city=?, block_number=?, building_name=?, tel=?, delivery_updated_date=? WHERE customer_id=? && delivery_id=?";

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
                
                $pattern = array("/last_name=\?/", "/first_name=\?/", "/ruby_last_name=\?/", "/ruby_first_name=\?/", "/zip_code_01\?/", "/zip_code_02=\?/", "/prefecture=\?/", "/city=\?/", "/block_number=\?/", "/building_name=\?/", "/tel=\?/", "/delivery_updated_date=\?/", "/customer_id=\?/", "/delivery_id=\?/");
                
                $replace = array('last_name='.$lastName, 'first_name='.$firstName, 'ruby_last_name='.$rubyLastName, 'ruby_first_name='.$rubyFirstName, 'zip_code_01'.$zipCode01, 'zip_code_02='.$zipCode02, 'prefecture='.$prefecture, 'city='.$city, 'block_number='.$blockNumber, 'building_name='.$buildingName, 'tel='.$tel, 'delivery_updated_date='.$dateTime, 'customer_id='.$customerId, 'delivery_id='.$deliveryId);
                $result = preg_replace($pattern, $replace, $sql);
                
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 「いつもの配送先住所」解除(deliveryテーブルとcustomerテーブルを全てFALSEに更新)
     * $customerIdをキーに更新
     * @param int $customerId　カスタマーID
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function releaseDeliveryFlag($customerId){  

        $dateTime = Config::getDateTime();

        try{
            $sql = "UPDATE delivery JOIN customers ON delivery.customer_id = customers.customer_id SET delivery.delivery_flag=FALSE, customers.delivery_flag=FALSE, delivery.delivery_updated_date=?, customers.customer_updated_date=? WHERE delivery.customer_id=?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $dateTime, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $dateTime, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $customerId, \PDO::PARAM_INT);
            $stmt->execute();

            $count = $stmt->rowCount();

            if($count<1){
                $pattern = array("/delivery_updated_date=\?/", "/customer_updated_date=\?/", "/customer_id=\?/");
                $replace = array('delivery_updated_date='.$dateTime, 'customer_updated_date='.$dateTime, 'customer_id='.$customerId);

                $result=preg_replace($pattern, $replace, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 配送先住所を「いつもの配送先住所」に更新(delivery_flagをTRUEに)
     * $customerIdをキーに更新
     * @param int $customerId　カスタマーID
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function setDeliveryFlag($customerId, $deliveryId){

        try{
            $dateTime = Config::getDateTime();

            $sql ="UPDATE delivery SET delivery_flag=TRUE, delivery_updated_date=? WHERE customer_id=? && delivery_id=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $dateTime, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(3, $deliveryId, \PDO::PARAM_INT);
            $stmt->execute();

            $count = $stmt->rowCount();
            if($count<1){
                $pattern = array("/delivery_updated_date=\?/", "/customer_id=\?/");
                $replace = array('delivery_updated_date='.$dateTime, 'customer_id='.$customerId);

                $result=preg_replace($pattern, $replace, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * 配送先住所削除(退会時)
     * $deliveryIdと$customerIdをキーに配送先住所を削除
     * @param int $customerId　カスタマーID
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function deleteDeliveryInfo($customerId){
        try{
            $sql = "DELETE FROM delivery WHERE customer_id= && delivery_id=?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $deliveryId, \PDO::PARAM_INT);
            $stmt->execute();
            
            $count = $stmt->rowCount();
            if($count<1){
                $pattern = array("/customer_id=\?/", "/delivery_id=\?/");
                $replace = array('customer_id='.$customerId, 'delivery_id='.$deliveryId);

                $result=preg_replace($pattern, $replace, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
        

    }
    
    /**
     * 配送先住所情報取得
     * $cutomerIdをキーに全配送先住所情報を取得する。
     * @param int $customerId　カスタマーID
     * @return DeliveryDto[] | FALSE
     * @throws MyPDOException 
     */
    public function getDeliveryInfo($customerId){

        try{
            $sql = "SELECT * FROM delivery WHERE customer_id=?";
            
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
                return FALSE;
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 「いつもの配送先住所」情報取得
     * $customerIdとdelivery_flagをキーに配送先住所情報を取得する。
     * @param int $customerId　カスタマーID
     * @return DeliveryDto | FALSE
     * @throws MyPDOException 
     */
    public function getDefDeliveryInfo($customerId){

        try{
            $sql = "SELECT * FROM delivery WHERE customer_id=? && delivery_flag=TRUE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;      
            }else{
                return FALSE;
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 配送先住所情報取得
     * $customerIdと$deliveryIdをキーに配送先住所情報を取得する。
     * @param int $customerId　カスタマーID
     * @param int $deliveryId　配送先住所ID
     * @return DeliveryDto
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function getDeliveryInfoById($customerId, $deliveryId){
        
        try{
            $sql = "SELECT * FROM delivery WHERE customer_id=? && delivery_id=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->bindvalue(2, $deliveryId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;
            }else{
                $pattern = array("/customer_id=\?/", "/delivery_id=\?/");
                $replace = array('customer_id='.$customerId, 'delivery_id='.$deliveryId);

                $result=preg_replace($pattern, $replace, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 会員配送先情報削除(退会時)
     * $customerIdをキーにカスタマー情報を削除
     * @param int $customerId　カスタマーID
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function deleteAllDeliveryInfo($customerId){
        try{
            $sql = "DELETE from delivery where customer_id=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                $result=preg_replace("/customer_id=\?/", 'customer_id='.$customerId, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}

?>