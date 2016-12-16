<?php

include_once './base/conn.php';

class InsertHora {

    private $id_user;
    
    function __construct($id_user) {
        $this->id_user = $id_user;
    }

    public function insert($id_emprego, $h) {
        
        $hora = R::dispense('horas');
        $hora->id_emprego = $id_emprego;        
        $hora->quantidade = $h['quantidade'];
        $hora->hora_inicial = $h['horaInicial'];
        $hora->hora_termino = $h['horaTermino'];
        $hora->dta = $h['dta'];
        $hora->tipo_hora = $h['tipoHora'];
        $hora->id_user = $this->id_user;

        $id_hora = R::store($hora);
        $hora_time = R::getCell("select updt_time from horas where id = ? limit 1", [$id_hora]);
        $jsonHora = [];
        $jsonHora["id"] = $h['id'];
        $jsonHora['id_server'] = $id_hora;
        $jsonHora['updt_time'] = $hora_time;
        
        return $jsonHora;
    }

}
