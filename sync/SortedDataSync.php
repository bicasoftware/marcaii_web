<?php

include 'CheckableListing.php';
include 'HorasInsert.php';
include 'SalariosInsert.php';
include 'PorcentagensInsert.php';
include 'InsertUpdatableData.php';
include 'NewRecordHelper.php';

class SortedDataSync {

    private $id_client;
    private $json;

    function __construct($id_client, $json) {
        $this->id_client = $id_client;
        $this->json = $json;
    }

    public function SyncNReturn() {
        
        //lista registros atualizaveis, ou seja, que devem ser sincronizados nos clients
        $checkableListing = new CheckablesListing($this->json['checkableData']);        
        $checkableData = $checkableListing->getUpdatablesList();
        
        //gera lista de ids que devem ser removidas no client
        $rem = $checkableListing->getRem();       
        
        //gera lista de ids que foram recebidas, e que são usadas para verificar novos registros a serem enviados ao servidor
        $ids = $checkableListing->getIds();
        
        //gera lista com novos empregos e novos registros avulsos e joga os dados em $new
        $newRecordHelper = new NewRecordHelper($this->id_client, $ids);
        $new = $newRecordHelper->getNewRecords();
        
        //insere novos empregos
        $empregos = $this->listInsertEmpregos($this->json['empregos']);
        //gera lista de dados que devem ser atualizados no servidor, baseando em id e updt_time
        $updatableData = $this->listSortedRecords($this->json['updatableData']);
        
        $results="";
        $results['updatableData'] = $checkableData;
        $results['empregos'] = $empregos;
        $results['checkableData'] = $updatableData;
        $results['rem'] = $rem;
        $results['new'] = $new;
        
        return $results;        
    }
    
    private function listInsertEmpregos($empregos){        
        $e = new InsertEmpregos($this->id_client, $empregos);
        return $e->pullUserById();
    }
    
    private function listSortedRecords($updatableData){
        $iud = new InsertUpdatableData($this->id_client, $updatableData);
        return $iud->listUpdatedRows();
    }

}
