<?php
namespace Models;
use \Models\CustomerDto;
use \Models\OriginalException;

class CustomerDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * ログイン認証
     * $mailをキーにカスタマー情報を取得する。
     * なければfalseを返す。
     * @param string $mail 入力されたユーザーのメールアドレス
     * @return CustomerDto[]
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
     * @return CustomerDto[]
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
     * customerDtoにSQL取得値をセット
     * 取得出来ない場合はfalseを返す。
     * @param Array $res　SQL取得結果
     * @return CustomerDto[]
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
        $dto->setAddress01($res['address_01']); 
        $dto->setAddress02($res['address_02']); 
        $dto->setAddress03($res['address_03']); 
        $dto->setAddress04($res['address_04']); 
        $dto->setAddress05($res['address_05']); 
        $dto->setAddress06($res['address_06']); 
        $dto->setTel($res['tel']);
        $dto->setMail($res['mail']);
        $dto->setDelFlag($res['del_flag']);
        
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
     * @param string $address01　入力されたユーザーの住所_郵便番号(3ケタ)
     * @param string $address02　入力されたユーザーの住所_郵便番号(4ケタ)
     * @param string $address03　入力されたユーザーの住所_都道府県
     * @param string $address04　入力されたユーザーの住所_市区町村等
     * @param string $address05　入力されたユーザーの住所_番地等
     * @param string $address06　入力されたユーザーの住所_建物名等
     * @param string $tel　入力されたユーザーの電話番号
     * @param string $mail　入力されたユーザーのメールアドレス
     * @throws PDOException 
     * @throws OriginalException(登録失敗時:code400)
     */
    public function insertCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $mail){

        try{
            $sql = "insert into customers(last_name, first_name, ruby_last_name, ruby_first_name, address_01, address_02, address_03, address_04, address_05, address_06, tel, mail, hash_password, del_flag, customer_insert_date)values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,now())";

            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
            $delFlag= 0;

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
            $stmt->bindvalue(12, $mail, \PDO::PARAM_STR);
            $stmt->bindvalue(13, $hash_pass);
            $stmt->bindvalue(14, $delFlag);
            $res = $stmt->execute();
            
            $count = $stmt->rowCount();
            if($count<1){
                throw new OriginalException('登録に失敗しました。',444);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 会員情報の住所をいつもの配送先住所に設定(del_flagを'1'→'0'に)
     * del_flag(=0)と$customerIdをキーにカスタマー情報を更新
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時:code200)
     */
    public function setDeliveryDefault($customerId){
        try{
            $customerDto = $this->getCustomerById($customerId);
            if($customerDto->getDelFlag() == 1){
                $sql = "UPDATE customers SET del_flag=? where customer_id=?";
                $delFlag= 0;
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindvalue(1, $delFlag, \PDO::PARAM_INT);
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
     * 会員情報の住所がいつもの配送先住所になっていれば解除(del_flagを'0'→'1'に)
     * $customerIdをキーに更新
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時:code200)
     */
    public function releaseDeliveryDefault($customerId){
        try{
            $customerDto = $this->getCustomerById($customerId);
            if($customerDto->getDelFlag() == 0){
                $sql = "UPDATE customers SET del_flag=? where customer_id=?";
                $delFlag= 1;

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindvalue(1, $delFlag, \PDO::PARAM_INT);
                $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
                $res = $stmt->execute();  
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
     * @throws OriginalException(削除失敗時:code300)
     */
    public function deleteCustomerInfo($customerId){
        try{
            $sql = "DELETE from customers where customer_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $res = $stmt->execute();
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
     * @return CustomerDto[]
     * @throws PDOException 
     * @throws OriginalException(取得失敗時:code100) 
     */
    public function getCustomerById($customerId){
        try{
            $sql = "SELECT * FROM customers WHERE customer_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            $dto = $this->setDto($res);
            if($dto){
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
     * @param string $address01　入力されたユーザーの住所_郵便番号(3ケタ)
     * @param string $address02　入力されたユーザーの住所_郵便番号(4ケタ)
     * @param string $address03　入力されたユーザーの住所_都道府県
     * @param string $address04　入力されたユーザーの住所_市区町村等
     * @param string $address05　入力されたユーザーの住所_番地等
     * @param string $address06　入力されたユーザーの住所_建物名等
     * @param string $tel　入力されたユーザーの電話番号
     * @param string $mail　入力されたユーザーのメールアドレス
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws PDOException 
     * @throws OriginalException(更新失敗時：code200)
     */
    public function updateCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $address01, $address02, $address03, $address04, $address05, $address06, $tel, $mail, $customerId){

        $hash_pass = password_hash($password, PASSWORD_DEFAULT);
        
        try{
            $sql ="UPDATE customers SET last_name=?, first_name=?, ruby_last_name=?, ruby_first_name=?, adPDO::PARAM_STR)dress_01=?, address_02=?, address_03=?, address_04=?, address_05=?, address_06=?, tel=?, mail=?, hash_password=?, customer_updated_date=now() where customer_id=?";

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
            $stmt->bindvalue(12, $mail, \PDO::PARAM_STR);
            $stmt->bindvalue(13, $hash_pass);
            $stmt->bindvalue(14, $customerId);
            $res = $stmt->execute();
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