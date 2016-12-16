<?php

namespace controllers{
    
    include_once './sync/EmpregosInsert.php';
    
    class FirstSyncController{
        
        public function __construct() {}
        
        public function syncNReturn($id_user, $json){            
            $insertEmpregos = new \InsertEmpregos($id_user, json_decode($json, JSON_UNESCAPED_UNICODE));
            echo json_encode($insertEmpregos->pullUserById(), JSON_UNESCAPED_UNICODE);
        }
    }
}