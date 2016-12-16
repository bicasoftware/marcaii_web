<?php

include_once './base/conn.php';

class InsertUpdatableData {

    private $id_user = -1;
    private $json = "";
    
    function __construct($id_user, $json) {
        $this->id_user = $id_user;
        $this->json = $json;
    }

    function listUpdatedRows() {
        $IHoras = new InsertHora($this->id_user);
        $ISalarios = new InsertSalario($this->id_user);
        $IPorcDif = new InsertPorcDiff($this->id_user);
        $updatedData = [];
        
        foreach ($this->json as $emprego) {
            $id_client = $emprego['id'];
            $id_emprego = $emprego['id_server'];
            $timestamp = $emprego['timestamp'];

            $info_horas = [];
            foreach ($emprego['horas'] as $hora) {
                array_push($info_horas, $IHoras->insert($id_emprego, $hora));
            }

            $info_salarios = [];
            foreach ($emprego['salarios'] as $s) {
                array_push($info_salarios, $ISalarios->insert($id_emprego, $s));
            }

            $info_porc = [];
            foreach ($emprego['porcentagens'] as $p) {
                array_push($info_porc, $IPorcDif->insert($id_emprego, $p));
            }

            $data['id'] = $id_client;
            $data['id_server'] = $id_emprego;
            $data['updt_time'] = $timestamp;

            $data['horas'] = $info_horas;
            $data['salarios'] = $info_salarios;
            $data['porcentagemdiferenciada'] = $info_porc;
            array_push($updatedData, $data);
        }

        return $updatedData;
    }

}
