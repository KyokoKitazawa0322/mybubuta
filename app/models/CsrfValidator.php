<?php
namespace Models;

use \Models\InvalidParamException;

class CsrfValidator {

    const HASH_ALGO = 'sha256';

    public static function generate(){
        
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return hash(self::HASH_ALGO, session_id());
    }

    
    /**
    * throw InvalidParamException
    **/
    public static function validate($token){
        
        $result = self::generate() === $token;
        
        if(!$result) {
            throw new InvalidParamException('CSRF validation failed.');
        }
    }

    public static function maketoken($formname){
       
        $token = sha1(uniqid(mt_rand(), true));
        $_SESSION[$formname] = $token;
        return $token;
    }
   
    /**
    * throw InvalidParamException
    **/
    public static function checkToken($token, $formName){
        if(!$token || !isset($_SESSION[$formName])){
            throw new InvalidParamException('CSRF validation failed. -no token found');
        }
        
        $key = $_SESSION[$formName];
           
        if($key !== $token ){
            throw new InvalidParamException('CSRF validation failed. -invalid token');
        }
        unset($_SESSION[$formName]);   
    }
}

?>
