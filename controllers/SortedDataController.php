<?php

namespace controllers{
    
    include_once './sync/SortedDataSync.php';
    
    class SortedDataController{
        
        public function __construct() {}
        
        public function SyncNReturn($id_client, $json){
            $f = new \SortedDataSync($id_client, json_decode($json,JSON_UNESCAPED_UNICODE));
            echo json_encode($f->SyncNReturn(), JSON_UNESCAPED_UNICODE);
        }
    }
}

