<?php
namespace Models;

use \Models\NoticeDto;

use \Config\Config;

use \Models\DBParamException;
use \Models\NoRecordException;
use \Models\MyPDOException;
    
class NoticeDao{
    
    private $pdo = NULL;
    
    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    
    /**
     * NoticeDtoにSQL取得値をセット
     * @param Array $res　SQL取得結果
     * @return NoticeDto
     * 例外処理は呼び出し元のメソッドで実施
     */
    public function setDto($res){
        
        $dto = new NoticeDto();
        
        $dto->setId($res['id']);
        $dto->setTitle($res['title']);
        $dto->setMainText($res['main_text']);
        $dto->setInsertDate($res['insert_date']);
        
        return $dto;
    }
    
    /**
     * お知らせの登録(管理画面)
     * @param string $title      お知らせ件名
     * @param string $mainText   お知らせ本文
     * @throws MyPDOException 
     */
    public function insertNoticeInfo($title, $mainText){
        
        $dateTime = Config::getDateTime();
        
        try{
            $sql = "INSERT INTO notice(title, main_text, insert_date)VALUES(?,?,?)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $title, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $mainText, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $dateTime, \PDO::PARAM_STR);
            $stmt->execute();

        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    
    /**
     * お知らせの削除(管理画面)
     * @param string $title      お知らせ件名
     * @param string $mainText   お知らせ本文
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function deleteNoticeInfo($id){
        try{
            $sql = "DELETE FROM notice WHERE id=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $id, \PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count<1){
                $result=preg_replace("/id=\?/", 'id='.$id, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * お知らせ一覧取得
     * @param string $title      お知らせ件名
     * @param string $mainText   お知らせ本文
     * @return NoticeDto[] 
     * @throws MyPDOException 
     * @throws NoRecordException
     */
    public function getNoticeInfoAll(){
        try{
            $sql = "SELECT * FROM notice ORDER BY insert_date DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll();
            
            if($res){
                $notice = [];
                foreach($res as $row){
                    $dto = $this->setDto($row);
                    $notice[] = $dto;
                }
                return $notice;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            Config::outputLog($e);
            throw $e;
        }
    }
    
    /**
     * お知らせ一覧(id昇順)取得
     * @param string $title      お知らせ件名
     * @param string $mainText   お知らせ本文
     * @return NoticeDto[] 
     * @throws MyPDOException 
     * @throws NoRecordException
     */
    public function getNoticeAllSortByIdAsc(){
        try{
            $sql = "SELECT * FROM notice ORDER BY id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll();
            
            if($res){
                $notice = [];
                foreach($res as $row){
                    $dto = $this->setDto($row);
                    $notice[] = $dto;
                }
                return $notice;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * お知らせ一覧(id降順)取得
     * @param string $title      お知らせ件名
     * @param string $mainText   お知らせ本文
     * @return NoticeDto[] 
     * @throws MyPDOException 
     * @throws NoRecordException
     */
    public function getNoticeAllSortByIdDesc(){
        try{
            $sql = "SELECT * FROM notice ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll();
            
            if($res){
                $notice = [];
                foreach($res as $row){
                    $dto = $this->setDto($row);
                    $notice[] = $dto;
                }
                return $notice;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    
    /**
     * 最新のお知らせ2件取得
     * @param string $title      お知らせ件名
     * @param string $mainText   お知らせ本文
     * @return NoticeDto[] 
     * @throws MyPDOException 
     * @throws NoRecordException
     */
    public function getLatestNoticeInfo(){
        try{
            $sql = "SELECT * FROM notice ORDER BY insert_date DESC LIMIT 2";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll();
            
            if($res){
                $notice = [];
                foreach($res as $row){
                    $dto = $this->setDto($row);
                    $notice[] = $dto;
                }
                return $notice;
            }else{
                throw new NoRecordException("no record error:".$sql);
            }
          }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    
    /**
     * お知らせ詳細取得
     * @param int $noticeId
     * @return NoticeDto
     * @throws MyPDOException 
     * @throws DBParamException
     */
    public function getNoticeDetail($noticeId){
        try{
            $sql = "SELECT * FROM notice WHERE id=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $noticeId);
            $stmt->execute();
            $res = $stmt->fetch();
            if($res){
                $dto = $this->setDto($res);
                return $dto;
            }else{
                $result=preg_replace("/id=\?/", 'id='.$customerId, $sql);
                throw new DBParamException("invalid param error".$result);
            }
        }catch(\PDOException $e){
            throw new MyPDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}

?>