<?php
namespace Models;
use \Models\NoticeDao;
use \Config\Config;

class NoticeDto{
    
    private $id;
    private $title;
    private $mainText;
    private $insertDate;

    //getter--------------------------------
    public function getId(){
        return $this->id;
    }
    
    public function getTitle(){
        return $this->title;
    }

    public function getMainText(){
        return $this->mainText;
    }
    
    public function getInsertDate(){
        return $this->insertdate;
    }
    
    //setter--------------------------------
    public function setId($id){
    	$this->id = $id;
    }
    
    public function setTitle($title){
        $this->title = $title;
    }
    
    public function setMainText($mainText){
        $this->mainText = $mainText;
    }
    
    public function setInsertDate($insertDate){
        $this->insertdate = $insertDate;
    }
    
}



?>