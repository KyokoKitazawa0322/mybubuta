<?php
namespace Models;

class AdminDto extends \Models\Model {

    private $id;
    private $adminId;
    private $adminPassword;
    
   public function setId($id){
        $this->id = $id;
    }

   public function setAdminId($adminId){
        $this->adminId = $adminId;
    }

   public function setAdminPassword($adminPassword){
        $this->adminPassword = $adminPassword;
    }

   public function getId(){
        return $this->id;
    }

   public function getAdminId(){
        return $this->adminId;
    }

   public function getAdminPassword(){
        return $this->adminPassword;
    }
}

?>