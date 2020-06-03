<?php
namespace Models;
use \Models\CustomerDto;
use \Models\OriginalException;
use \Config\Config;
    
class CustomerDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * ログイン認証
     * $mailをキーにカスタマー情報を取得する。
     * なければfalseを返す。
     * @param string $mail 入力されたユーザーのメールアドレス
     * @return CustomerDto
     * @throws PDOException
     */
    public function getCustomerByMail($mail){

        try{
            $sql = "SELECT * FROM customers WHERE mail=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $mail, \PDO::PARAM_STR);
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
     * メールアドレス重複確認
     * $mailをキーにカスタマー情報(メールアドレス)を取得する。
     * なければfalseを返す。
     * @param string $mail　入力されたユーザーのメールアドレス
     * @return CustomerDto
     * @throws PDOException 
     */
    public function checkMailExists($mail){

        try{
            $sql = "SELECT * FROM customers WHERE mail = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $mail, \PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = new customerDto();
                $dto->setMail($res['mail']);
                return $dto;
            }else{
                return false;
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * CustomerDtoにSQL取得値をセット
     * @param Array $res　SQL取得結果
     * @return CustomerDto
     * 例外処理は呼び出し元のメソッドで実施
     */
    public function setDto($res){
        
        $dto = new CustomerDto();
        
        $dto->setCustomerId($res['customer_id']);
        $dto->setHashPassword($res['hash_password']);
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
        $dto->setMail($res['mail']);
        $dto->setDeliveryFlag($res['delivery_flag']);
        
        return $dto;
    }
    
    /**
     * 会員情報の登録
     * @param string $password　入力されたユーザーのパスワード
     * パスワードはメソッド内でハッシュ化してinsert
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
     * @param string $mail　入力されたユーザーのメールアドレス
     * @throws PDOException 
     * @throws OriginalException(登録失敗時:code444)
     */
    public function insertCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $mail){
        
        $dateTime = Config::getDateTime();
        $hash_pass = password_hash($password, PASSWORD_DEFAULT);
        $deliveryFlag= TRUE;
        
        try{
            $sql = "insert into customers(last_name, first_name, ruby_last_name, ruby_first_name, zip_code_01, zip_code_02, prefecture, city, block_number, building_name, tel, mail, hash_password, delivery_flag, customer_insert_date)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

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
            $stmt->bindvalue(12, $mail, \PDO::PARAM_STR);
            $stmt->bindvalue(13, $hash_pass);
            $stmt->bindvalue(14, $deliveryFlag, \PDO::PARAM_INT);
            $stmt->bindvalue(15, $dateTime, \PDO::PARAM_STR);
            $stmt->execute();
            
            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('登録に失敗しました。',444);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 会員情報の住所をいつもの配送先住所に設定
     * del_flag(=0)と$customerIdをキーにカスタマー情報を更新
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時:code222)
     */
    public function setDeliveryDefault($customerId){
        try{
            $customerDto = $this->getCustomerById($customerId);
            if(!$customerDto->getDeliveryFlag()){
                $sql = "UPDATE customers SET delivery_flag=? where customer_id=?";
                $deliveryFlag= TRUE;
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindvalue(1, $deliveryFlag, \PDO::PARAM_INT);
                $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
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
     * 会員情報の住所がいつもの配送先住所になっていれば解除
     * $customerIdをキーに更新
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時:code222)
     */
    public function releaseDeliveryDefault($customerId){
        try{
            $customerDto = $this->getCustomerById($customerId);
            if($customerDto->getDeliveryFlag() == TRUE){
                $sql = "UPDATE customers SET delivery_flag=? where customer_id=?";
                $deliveryFlag= FALSE;

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindvalue(1, $deliveryFlag, \PDO::PARAM_INT);
                $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
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
     * 会員情報削除(退会時)
     * $customerIdをキーにカスタマー情報を削除
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(削除失敗時:code333)
     */
    public function deleteCustomerInfo($customerId){
        try{
            $sql = "DELETE from customers where customer_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('削除に失敗しました。',333);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 会員情報取得
     * $cutomerIdをキーにカスタマー情報を取得する。
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @return CustomerDto
     * @throws PDOException 
     * @throws OriginalException(取得失敗時:code111) 
     */
    public function getCustomerById($customerId){
        try{
            $sql = "SELECT * FROM customers WHERE customer_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
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
    
    /**
     * 会員情報の更新
     * @param string $password　入力されたユーザーのパスワード
     * パスワードはメソッド内でハッシュ化してupdate
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
     * @param string $mail　入力されたユーザーのメールアドレス
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時：code222)
     */
    public function updateCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $mail, $customerId){

        $hash_pass = password_hash($password, PASSWORD_DEFAULT);
        $dateTime = Config::getDateTime();
        
        try{
            $sql ="UPDATE customers SET last_name=?, first_name=?, ruby_last_name=?, ruby_first_name=?, zip_code_01=?, zip_code_02=?, prefecture=?, city=?, block_number=?, building_name=?, tel=?, mail=?, hash_password=?, customer_updated_date=? where customer_id=?";

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
            $stmt->bindvalue(12, $mail, \PDO::PARAM_STR);
            $stmt->bindvalue(13, $hash_pass, \PDO::PARAM_STR);
            $stmt->bindvalue(14, $dateTime, \PDO::PARAM_STR);
            $stmt->bindvalue(15, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('更新に失敗しました。',222);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
}

?>