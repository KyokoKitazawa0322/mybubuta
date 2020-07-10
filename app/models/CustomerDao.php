<?php
namespace Models;

use \Models\CustomerDto;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;

    
class CustomerDao{
    
    private $pdo = NULL;
    
    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    
    /**
     * select文共通処理
     * @return CustomerDto[]
     * 例外処理は呼び出し元で行う
     */
    public function select($sql){
        $stmt = $this->pdo->query($sql); 
        $res = $stmt->fetchAll();
        if($res){
            $customers = [];
            foreach($res as $row) {
                $dto = $this->setDto($row);
                $customers[] = $dto;
            }
            return $customers;
        }else{
            return FALSE;   
        }
    }
    
    /**
     * ログイン認証
     * $mailをキーにカスタマー情報を取得する。
     * @param string $mail ユーザーのメールアドレス
     * @return CustomerDto | boolean FALSE
     * @throws MyPDOException
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
                return FALSE;
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    
    /**
     * メールアドレス重複確認
     * @param string $mail　ユーザーのメールアドレス
     * @return CustomerDto | boolean FALSE
     * @throws MyPDOException 
     */
    public function checkMailExists($mail){

        try{
            $sql = "SELECT * FROM customers WHERE mail=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $mail, \PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = new customerDto();
                $dto->setMail($res['mail']);
                return $dto;
            }else{
                return FALSE;
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    
    //---------------------------------------
    /**
     * メールアドレス重複確認
     * $mailをキーにカスタマー情報を取得できた場合は今回のメールと比較し他ユーザとの重複か確認
     * @param string $mail　ユーザーのメールアドレス
     * @return boolean TRUE(他ユーザとの重複あり) | boolean FALSE(他ユーザとの重複なし)
     * @throws MyPDOException 
     * @throws DBConnectionException 
     */
    public function checkMailExistsForUpdate($mail, $customerMail){
        
        try{
            $mailExists = $this->checkMailExists($mail);
            /*- カスタマー情報が取得でき、かつユーザ自身のアドレスでなかった場合はエラーとする。 -*/
            if($mailExists){
                if($customerDto && $customerDto->getMail() != $customerMail){
                    return TRUE;
                }
            }else{
                return FALSE;
            }
        }catch(MyPDOException $e){
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
        $dto->setCustomerInsertDate($res['customer_insert_date']);
        
        return $dto;
    }
    
    /**
     * 会員情報の登録
     * @param string $password　ユーザーのパスワード
     * パスワードはメソッド内でハッシュ化してinsert
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
     * @param string $mail　ユーザーのメールアドレス
     * @throws MyPDOException 
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
            
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 会員情報の住所をいつもの配送先住所に設定
     * $customerIdをキーにカスタマー情報を更新
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function setDeliveryFlag($customerId){
        try{
            $dateTime = Config::getDateTime();

            $sql = "UPDATE customers SET delivery_flag=TRUE, customer_updated_date=? where customer_id=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $dateTime, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $customerId, \PDO::PARAM_INT);
            $stmt->execute();

            $count = $stmt->rowCount();
            if($count<1){

                $pattern = array("/customer_updated_date=\?/", "/customer_id=\?/");
                $replace = array('customer_updated_date='.$dateTime, 'customer_id='.$customerId);

                $result=preg_replace($pattern, $replace, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    

    
    /**
     * 会員情報取得
     * $cutomerIdをキーにカスタマー情報を取得する。
     * @param int $customerId　カスタマーID
     * @return CustomerDto
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function getCustomerById($customerId){
        try{
            $sql = "SELECT * FROM customers WHERE customer_id=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;
            }else{
                $result=preg_replace("/customer_id=\?/", 'customer_id='.$customerId, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 会員情報全件取得(管理ページ用)
     * @return CustomerDto
     * @throws MyPDOException 
     * @throws NoRecordException
     */
    public function getCustomersAll(){
        try{
            $sql = "SELECT * FROM customers;";
            $customers = $this->select($sql);
            if($customers){
                return $customers;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 会員情報全件取得(customer_insert_date降順)
     * @return CustomerDto
     * @throws MyPDOException 
     * @throws NoRecordException
     */
    public function getCustomersAllSortByInsertDateDesc(){
        try{
            $sql = "SELECT * FROM customers ORDER BY customer_insert_date DESC;";
            $customers = $this->select($sql);
            if($customers){
                return $customers;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * 会員情報全件取得(customer_insert_date昇順)
     * @return CustomerDto
     * @throws MyPDOException 
     * @throws NoRecordException
     */
    public function getCustomersAllSortByInsertDateAsc(){
        try{
            $sql = "SELECT * FROM customers ORDER BY customer_insert_date ASC;";
            $customers = $this->select($sql);
            if($customers){
                return $customers;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    
    /**
     * 会員情報の更新
     * @param string $password　ユーザーのパスワード
     * パスワードはメソッド内でハッシュ化してupdate
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
     * @param string $mail　ユーザーのメールアドレス
     * @param int $customerId　ログイン時に自動セットしたカスタマーID
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function updateCustomerInfo($password, $lastName, $firstName, $rubyLastName, $rubyFirstName, $zipCode01, $zipCode02, $prefecture, $city, $blockNumber, $buildingName, $tel, $mail, $customerId){

        $hashPass = password_hash($password, PASSWORD_DEFAULT);
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
            $stmt->bindvalue(13, $hashPass, \PDO::PARAM_STR);
            $stmt->bindvalue(14, $dateTime, \PDO::PARAM_STR);
            $stmt->bindvalue(15, $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                
                $pattern = array("/last_name=\?/", "/first_name=\?/", "/ruby_last_name=\?/", "/ruby_first_name=\?/", "/zip_code_01\?/", "/zip_code_02=\?/", "/prefecture=\?/", "/city=\?/", "/block_number=\?/", "/building_name=\?/", "/tel=\?/", "/mail=\?/", "/hash_password=\?/", "/customer_updated_date=\?/", "/customer_id=\?/");
                
                $replace = array('last_name='.$lastName, 'first_name='.$firstName, 'ruby_last_name='.$rubyLastName, 'ruby_first_name='.$rubyFirstName, 'zip_code_01'.$zipCode01, 'zip_code_02='.$zipCode02, 'prefecture='.$prefecture, 'city='.$city, 'block_number='.$blockNumber, 'building_name='.$buildingName, 'tel='.$tel, 'mail='.$mail, 'hash_password='.$hashPass, 'customer_updated_date='.$dateTime, 'customer_id='.$customerId);
                $result = preg_replace($pattern, $replace, $sql);
                
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}

?>