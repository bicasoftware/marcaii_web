<?php

namespace controllers{

    include_once './sync/FullResynch.php';
    
    class FullResyncController{
        
        function __construct() {}
        
        function listAllDataByUser($id_user){
            $resyncher = new \FullResync($id_user);
            echo json_encode($resyncher->getSyncData());
        }
    }
}