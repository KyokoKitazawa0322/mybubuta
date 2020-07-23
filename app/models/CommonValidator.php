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
    public function checkLength($key, $value, $limit){
        if(!preg_match("/\A[\s\S]{1,{$limit}}\z/u",$value)){
            $error = $key.'は'.$limit.'文字以内で入力して下さい';
            return $error;
        }
    }

    //---------------------------------------
    public function textAreaValidation($key, $value, $limit) {
        $error = FALSE;
        $value_trim = trim($value);
        
        if($value_trim == "") {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
            
        }elseif($error = $this->checkLength($key, $value, $limit)){
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------
    public function fullWidthValidation($key, $value, $limit) {
        $error = FALSE;
    
        if(empty($value)){
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
    
        }elseif($error = $this->checkLength($key, $value, $limit)){
            $this->result = FALSE;
                
        }elseif(!preg_match("/\A([０-９Ａ-Ｚぁ-んァ-ヶー―－々〇〻\x{3400}-\x{9FFF}\x{F900}-\x{FAFF}\x{20000}-\x{2FFFF}])+\z/u", $value)){
                $error = $key.'は全角文字で入力して下さい。';
                $this->result = FALSE;
        }
        return $error; 
    }

    //---------------------------------------

    public function rubyValidation($key, $value, $limit) {
        $error = FALSE;
        
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
            
        }elseif($error = $this->checkLength($key, $value, $limit)){
            $this->result = FALSE;
            
        }elseif(!preg_match('/\A[ア-ン゛゜ァ-ォャ-ョー「」、]+\z/u',$value)) {
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
            
        }elseif($error = $this->checkLength($key, $value, 255)){
            $this->result = FALSE;
            
        }elseif(!preg_match('/\A([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+\z/',$value)){
            $error = $key.'を正しく入力して下さい。';
            $this->result = FALSE;
        }
        return $error;  
    }
    
    //---------------------------------------
    public function telValidation($key, $value) {
        $error = FALSE;
        
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        
        }elseif(!preg_match('/\A(0{1}\d{9,10})\z/',$value)) {
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
        
        }elseif(!preg_match('/\A\d{3}\z/',$value)) {
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
        
        }elseif(!preg_match('/\A\d{4}\z/',$value)) {
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
        
        }elseif(!preg_match("/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,20}\z/", $value)){
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
    public function priceValidation($key, $value, $limit) {
        $error = FALSE;
            
        if(empty($value)){
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        
        }elseif($value >= $limit){
            $error = $key.'の上限金額は'.number_format($limit).'円です。';
            return $error;
            
        }elseif(!preg_match('/\A([1-9][0-9]*)\z/',$value)) {
            $error = $key.'を正しく入力して下さい。';
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------
    /*- 0も許容 -*/
    public function stockValidation($key, $value, $limit) {
        $error = FALSE;
        
        if(!$value==0 && empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
            
        }elseif($value >= $limit){
            $error = $key.'の上限は'.number_format($limit).'個です。';
            return $error;
        
        }elseif(!preg_match('/\A(0|[1-9][0-9]*)\z/',$value)) {
            $error = $key.'を正しく入力して下さい。';
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------
    public function itemCodeValidation($key, $value){
        $error = FALSE;
       
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        
        }elseif(!preg_match("/\A([A-Z]-[0-9]{4})\z/", $value)){
            $error = $key.'を正しく入力して下さい。';
            $this->result = FALSE;
        }
        return $error; 
    }
    
    //---------------------------------------
    public function stringValidation($key, $value, $limit){
        $error = FALSE;
       
        if(empty($value)) {
            $error = $key.'は必須入力です。';
            $this->result = FALSE;
        
        }elseif($error = $this->checkLength($key, $value, $limit)){
            $this->result = FALSE;
        }
        return $error; 
    }
    
    public function getResult(){
        return $this->result;
    }
}
    
?>
