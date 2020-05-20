<?php 
namespace Models;
use \Models\CustomerDao;
    
class CommonValidator {
    
    private $result =true;
//---------------------------------------
    //ログイン用
    public function requiredError($password, $mail) {
        $error = false;
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
        $error = false;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = false;
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
            if($result['customer_id'] !== $customerId){
                $error = "既に使用されているメールアドレスです。";
            }
        }
        $this->result = false;
        return $error; 
    }
    
    //---------------------------------------
    public function fullWidthValidation($key, $value) {
        $error = false;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = false;
        }elseif(!preg_match('/^[ぁ-んァ-ヶー一-龠 　\r\n\t]+$/u',$value)){
            $error = $key.'は全角文字で入力して下さい。';
            $this->result = false;
        }
        return $error; 
    }

    //---------------------------------------

    public function rubyValidation($key, $value) {
        $error = false;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = false;
        }elseif(!preg_match('/^[ア-ン゛゜ァ-ォャ-ョー「」、]+$/u',$value)) {
            $error = $key.'は全角カタカナで入力して下さい。';
            $this->result = false;
        }
        return $error; 
    }
    
    //---------------------------------------

    public function mailValidation($key, $value) {
        $error = false;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = false;
        }elseif(!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',$value)) {
            $error = $key.'を正しく入力して下さい。';
            $this->result = false;
        }
        return $error;  
    }
    
    public function checkMail($mail, $customerMail){
        $error = false;
        $customerDao = new \Models\CustomerDao();
        $customerDto = $customerDao->checkMailExists($mail);
        if($customerDto && $customerDto->getMail() !== $customerMail){
            $error =  "既に使用されているメールアドレスです。";
            $this->result = false;     
        }
        return $error;
    }
    //---------------------------------------
    public function telValidation($key, $value) {
        $error = false;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = false;
        }elseif(!preg_match('/^(0{1}\d{9,10})$/',$value)) {
            $error = $key.'は半角数字で市外局番から正しく入力してください。';
            $this->result = false;
        }
        return $error; 
    }
    
    //---------------------------------------
    public function firstZipCodeValidation($key, $value) {
        $error = false;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = false;
        }elseif(!preg_match('/^\d{3}$/',$value)) {
            $errors = $key.'を正しく入力して下さい。';
            $this->result = false;
        }
        return $error; 
    }
    //---------------------------------------
    public function lastZipCodeValidation($key, $value) {
        $error = false;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = false;
        }elseif(!preg_match('/^\d{4}$/',$value)) {
            $errors = $key.'を正しく入力して下さい。';
            $this->result = false;
        }
        return $error; 
    }

    //---------------------------------------
      //パスワードチェック
    public function passValidation($key, $value) {
        $error = false;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = false;
        }elseif(!preg_match("/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,20}$/", $value)){
            $error = $key.'は英字・数字を含め8～20文字で入力してください。';
            $this->result = false;
        }
        return $error; 
    }
    //--------------------------------------- 
    //パスワード(再確認)チェック
    public function passConfirmValidation($key, $value, $confirm) {
        $error = false;
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = false;
        }elseif($value !== $confirm){
            $error = $key.'が一致しません。';
            $this->result = false;
        }
        return $error; 
    }
    
    public function getResult(){
        return $this->result;
    }
}
    
?>
