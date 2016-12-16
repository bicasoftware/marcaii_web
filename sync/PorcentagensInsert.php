<?php

include_once './base/conn.php';

class InsertPorcDiff {

    private $id_user;

    function __construct($id_user) {
        $this->id_user = $id_user;
    }

    public function insert($id_emprego, $p) {
        $porc = R::dispense("porcentagemdiferenciada");
        $porc->id_emprego = $id_emprego;
        $porc->dia_semana = $p['diaSemana'];
        $porc->porcadicional = $p['porcAdicional'];
        $porc->id_user = $this->id_user;

        $id_porc = R::store($porc);
        $porc_time = R::getCell("select updt_time from porcentagemdiferenciada where id = ? limit 1", [$id_porc]);
        $json_porc = "";
        $json_porc['id'] = $p['id'];
        $json_porc['id_server'] = $id_porc;
        $json_porc['updt_time'] = $porc_time;

        return $json_porc;
    }

}
