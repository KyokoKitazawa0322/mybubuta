<?php
namespace Models;
use \Models\NoticeDto;
use \Models\OriginalException;
use \Config\Config;
    
class NoticeDao extends \Models\Model{
    
    public function __construct(){
        parent::__construct();
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
     * @throws PDOException 
     * @throws OriginalException(登録失敗時:code444)
     */
    public function insertNoticeInfo($title, $mainText){
        
        $dateTime = Config::getDateTime();
        
        try{
            $sql = "insert into notice(title, main_text, insert_date)values(?,?,?)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $title, \PDO::PARAM_STR);
            $stmt->bindvalue(2, $mainText, \PDO::PARAM_STR);
            $stmt->bindvalue(3, $dateTime, \PDO::PARAM_STR);
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
     * お知らせの削除(管理画面)
     * @param string $title      お知らせ件名
     * @param string $mainText   お知らせ本文
     * @throws PDOException 
     * @throws OriginalException(削除失敗時:code333)
     */
    public function deleteNoticeInfo($id){
        try{
            $sql = "DELETE from notice where id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $id, \PDO::PARAM_INT);
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
     * お知らせ一覧取得
     * @param string $title      お知らせ件名
     * @param string $mainText   お知らせ本文
     * @return NoticeDto[] 
     * @throws PDOException 
     * @throws OriginalException(取得失敗時:code111)
     */
    public function getNoticeInfoAll(){
        try{
            $sql = "SELECT * FROM notice";
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
                throw new OriginalException('取得に失敗しました。',111);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    /**
     * 最新のお知らせ2件取得
     * @param string $title      お知らせ件名
     * @param string $mainText   お知らせ本文
     * @return NoticeDto[] 
     * @throws PDOException 
     * @throws OriginalException(取得失敗時:code111)
     */
    public function getLatestNoticeInfo(){
        try{
            $sql = "SELECT * FROM notice LIMIT 2";
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
                throw new OriginalException('取得に失敗しました。',111);
            }
        }catch(\PDOException $e){
            throw $e;
        }
    }
    
    
    
    /**
     * お知らせ詳細取得
     * @param int $noticeId
     * @return NoticeDto
     * @throws PDOException 
     * @throws OriginalException(取得失敗時:code111)
     */
    public function getNoticeDetail($noticeId){
        try{
            $sql = "SELECT * FROM notice where id=?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(1, $noticeId);
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
}

?>