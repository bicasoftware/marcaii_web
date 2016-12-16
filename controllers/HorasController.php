<?php

namespace controllers {
    include_once "./base/conn.php";
    include_once "./base/BaseDelete.php";

    class HorasController {

        function __construct() {
            
        }

        function insert($id_client, $id_emprego, $json) {
            $h = json_decode($json);
            $hora = \R::dispense('horas');
            $hora->id_emprego = $id_emprego;
            $hora->quantidade = $h->quantidade;
            $hora->hora_inicial = $h->hora_inicial;
            $hora->hora_termino = $h->hora_termino;
            $hora->dta = $h->dta;
            $hora->tipo_hora = $h->tipo_hora;
            $hora->id_user = $id_client;

            $id_hora = \R::store($hora);
            $hora_time = \R::getCell("select updt_time from horas where id = ? limit 1", [$id_hora]);
            $jsonHora = [];
            $jsonHora["id"] = $h->id;
            $jsonHora['id_server'] = $id_hora;
            $jsonHora['updt_time'] = $hora_time;
            
            echo json_encode($jsonHora, JSON_UNESCAPED_UNICODE);
        }

        function update($id_client, $id_emprego, $json) {
            
        }

        function delete($id_client, $id_emprego, $id) {
            $d = new \BaseDelete("horas", $id_client, $id_emprego);
            echo json_encode($d->deleteById($id), JSON_UNESCAPED_UNICODE);
        }

    }

}
