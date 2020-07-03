<?php
namespace Models;
use \Models\AdminDto;
use \Models\OriginalException;
    
class AdminDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
    }

    /**
     * AdminDtoにSQL取得値をセット
     * @param Array $res　SQL取得結果
     * @return AdminDto
     * 例外処理は呼び出し元のメソッドで実施
     */
    public function setDto($res){
        
        $adminDto = new AdminDto();
        
        $adminDto->setId($res['id']);
        $adminDto->setAdminId($res['admin_id']);
        $adminDto->setAdminPassword($res['admin_password']);
        
        return $adminDto;
    }
    
    /**
     * 管理者ログイン
     * $adminIdと$adminPasswordをキーに管理者情報を取得する。
     * なければfalseを返す
     * @param str $adminId　入力された管理者ID
     * @param str $adminPassword　入力された管理者password
     * @return AdminDto
     * @throws MyPDOException 
     */
    public function adminLogin($adminId, $adminPassword){
        try{
            $sql = "SELECT * FROM admin WHERE admin_id = ? && admin_password =?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $adminId, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $adminPassword, \PDO::PARAM_STR);
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
}

?>