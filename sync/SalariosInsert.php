<?php

include_once './base/conn.php';

class InsertSalario {

    private $id_user;

    function __construct($id_user) {
        $this->id_user = $id_user;
    }

    public function insert($id_emprego, $s) {
        $salario = R::dispense('salarios');

        $salario->id_emprego = $id_emprego;
        $salario->valorsalario = $s['valorSalario'];
        if ($s['status'] == 1) {
            $salario->status = true;
        }
        $salario->status = $s['status'];
        $salario->vigencia = $s['vigencia'];
        $salario->id_user = $this->id_user;

        $id_salario = R::store($salario);
        $sal_time = R::getCell("select updt_time from salarios where id = ? limit 1", [$id_salario]);
        $json_sal = [];
        $json_sal["id"] = $s['id'];
        $json_sal["id_server"] = $id_salario;
        $json_sal["updt_time"] = $sal_time;

        return $json_sal;
    }

}
