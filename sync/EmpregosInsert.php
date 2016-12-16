<?php

include_once './base/conn.php';

class InsertEmpregos {

    private $id_user = 0;
    private $json = "";
    
    function __construct($id_user, $json) {
        $this->id_user = $id_user;
        $this->json = $json;
    }

    public function pullUserById() {

        $list_empregos = [];

        foreach ($this->json as $e) {
            $emprego = R::dispense('empregos');
            $emprego->dia_fechamento = $e['diaFechamento'];
            $emprego->porc_normal = $e['porcNormal'];
            $emprego->porc_feriados = $e['porcFeriados'];
            $emprego->nome_emprego = $e['nomeEmprego'];
            $emprego->horario_saida = $e['horarioSaida'];
            $emprego->carga_horaria = $e['cargaHoraria'];
            $emprego->banco_horas = $e['bancoHoras'];
            $emprego->notificacoes = $e['notificacoes'];
            $emprego->id_user = $this->id_user;
            $id_emprego = R::store($emprego);


            $emp = R::findOne("empregos", "id = ?", [$id_emprego]);
            $ret['response'] = "0";
            $ret['id_server'] = "1";
            $ret["updt_time"] = date('Y-m-d H:i:s');

            $novo_emprego = [];
            $novo_emprego['id'] = $e['id'];
            $novo_emprego['id_server'] = $emp['id'];
            $novo_emprego['updt_time'] = $emp['updt_time'];


            $list_horas = [];
            $jsonhoras = $e['horas'];
            foreach ($jsonhoras as $h) {
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

                array_push($list_horas, $jsonHora);
            }

            $list_salarios = [];
            $jsonsalarios = $e['salarios'];
            foreach ($jsonsalarios as $s) {
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
                array_push($list_salarios, $json_sal);
            }

            $list_porc = [];
            $jsonporcdiff = $e['porcentagemDiferenciadas'];
            foreach ($jsonporcdiff as $p) {
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
                array_push($list_porc, $json_porc);
            }

            $novo_emprego['horas'] = $list_horas;
            $novo_emprego['salarios'] = $list_salarios;
            $novo_emprego['porcdiff'] = $list_porc;

            array_push($list_empregos, $novo_emprego);
        }

        //echo json_encode($list_empregos, JSON_UNESCAPED_UNICODE);
        return $list_empregos;
    }

}
