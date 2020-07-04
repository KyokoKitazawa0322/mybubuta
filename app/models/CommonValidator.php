<?php 
namespace Models;
use \Models\CustomerDao;
use \Config\Config;
    
class CommonValidator {
    
    private $result = TRUE;
//---------------------------------------
    //ログイン用
    public function requiredError($password, $mail) {
        $error = FALSE;
        $item = array(
            'パスワード' => $password,
            'メール' => $mail
        );
        foreach ($item  as $key => $value) {
            if(empty($value)) {
                $error = $key.'は必須入力です。';
            }
            return $error; 
        }
    }
    //---------------------------------------
    public function requireCheck($key, $value) {
        $error = FALSE;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------
    public function requireCheckForTextarea($key, $value) {
        $error = FALSE;
        $value_trim = trim($value);
        if($value_trim == "") {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }
        return $error; 
    }

    //---------------------------------------
    //会員登録情報の変更用
    public function mailExistEx($mail, $customerId){
        $con = new Connection();
        $pdo = $con->pdo();
        $sql = "SELECT * FROM customers WHERE mail=?"; //LIMIT1
        $stmt = $pdo->prepare($sql);
        $stmt->bindvalue(1, $mail);
        $stmt->execute();
        if($result = $stmt->fetch()){
            if($result['customer_id'] != $customerId){
                $error = "既に使用されているメールアドレスです。";
            }
        }
        $this->result = FALSE;
        return $error; 
    }
    
    //---------------------------------------
    public function fullWidthValidation($key, $value) {
        $error = FALSE;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }elseif(!preg_match('/^[ぁ-んァ-ヶー一-龠 　\r\n\t]+$/u',$value)){
            $error = $key.'は全角文字で入力して下さい。';
            $this->result = FALSE;
        }
        return $error; 
    }

    //---------------------------------------

    public function rubyValidation($key, $value) {
        $error = FALSE;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }elseif(!preg_match('/^[ア-ン゛゜ァ-ォャ-ョー「」、]+$/u',$value)) {
            $error = $key.'は全角カタカナで入力して下さい。';
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------

    public function mailValidation($key, $value) {
        $error = FALSE;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }elseif(!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',$value)) {
            $error = $key.'を正しく入力して下さい。';
            $this->result = FALSE;
        }
        return $error;  
    }

    /**
     * メールアドレス重複確認
     * $mailをキーにカスタマー情報を取得できた場合は今回のメールと比較し他ユーザとの重複か確認
     * @param string $mail　ユーザーのメールアドレス
     * @return CustomerDto | boolean FALSE
     * @throws MyPDOException 
     */
    public function checkMail($mail, $customerMail){
        
        $error = FALSE;
        $customerDao = new CustomerDao();
        
        try{
            $customerDto = $customerDao->checkMailExists($mail);
            
            /*- カスタマー情報が取得でき、かつユーザ自身のアドレスでなかった場合はエラーとする。 -*/
            if($customerDto && $customerDto->getMail() != $customerMail){
                $error =  "既に使用されているメールアドレスです。";
                $this->result = FALSE;     
            }
            return $error;
        }catch(MyPDOException $e){
            throw $e;
        }
    }
    //---------------------------------------
    public function telValidation($key, $value) {
        $error = FALSE;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }elseif(!preg_match('/^(0{1}\d{9,10})$/',$value)) {
            $error = $key.'は半角数字で市外局番から正しく入力してください。';
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------
    public function firstZipCodeValidation($key, $value) {
        $error = FALSE;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }elseif(!preg_match('/^\d{3}$/',$value)) {
            $error = $key.'を正しく入力して下さい。';
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------
    public function lastZipCodeValidation($key, $value) {
        $error = FALSE;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }elseif(!preg_match('/^\d{4}$/',$value)) {
            $error = $key.'を正しく入力して下さい。';
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------
    /**
     * 都道府県の値確認
     * 用意された値でなければ例外を投げる
     * 返り値なし
     * @throws InvalidParamException
     */
    public function checkPrefecture($prefecture){

        $prefectureCheck = FALSE;
        foreach(Config::PREFECTURES as $kenmei){
            if($prefecture == $kenmei){
                $prefectureCheck = TRUE;   
            }
        }
        if(!$prefectureCheck){
            throw new InvalidParamException('Invalid param in prefecture:$prefecture='.$prefecture);
        }
    }
    
    //---------------------------------------
      //パスワードチェック
    public function passValidation($key, $value) {
        $error = FALSE;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }elseif(!preg_match("/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,20}$/", $value)){
            $error = $key.'は英字・数字を含め8～20文字で入力してください。';
            $this->result = FALSE;
        }
        return $error; 
    }
    //--------------------------------------- 
    //パスワード(再確認)チェック
    public function passConfirmValidation($key, $value, $confirm) {
        $error = FALSE;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }elseif($value != $confirm){
            $error = $key.'が一致しません。';
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------
    public function priceValidation($key, $value) {
        $error = FALSE;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }elseif(!preg_match('/^(0|[1-9]\d*)$/',$value)) {
            $error = $key.'を正しく入力して下さい。';
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------
    public function numberValidation($key, $value) {
        $error = FALSE;
        if(!$value==0 && empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        }elseif(!preg_match('/^\d{0,7}$/',$value)) {
            $error = $key.'を正しく入力して下さい。';
            $this->result = FALSE;
        }
        return $error; 
    }

    public function getResult(){
        return $this->result;
    }
}
    
?>
