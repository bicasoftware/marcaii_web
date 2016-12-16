<?php

include_once './base/conn.php';

class CheckablesListing {

    private $update_horas = [];
    private $update_salarios = [];
    private $update_porcentagem = [];
    private $update_empregos = [];
    private $rem;
    private $ids;

    function __construct($json) {
        $this->listUpdatableHoras($json['horas']);
        $this->listUpdatableSalarios($json['salarios']);
        $this->listUpdatablePorcentagens($json['porcentagem']);
        $this->listUpdatableEmpregos($json['empregos']);
    }

    public function getUpdatablesList() {
        $update['horas'] = $this->update_horas;
        $update['empregos'] = $this->update_empregos;
        $update['salarios'] = $this->update_salarios;
        $update['porcentagemdiferenciada'] = $this->update_porcentagem;

        return $update;
    }

    public function getRem() {
        return $this->rem;
    }
    
    public function getIds(){
        return $this->ids;
    }

    private function getCount($tablename, $id, $timestamp) {
        return R::getCell("SELECT count(id) as c FROM " . $tablename . " where id = ? and updt_time = ? limit 1", [$id, $timestamp]);
    }

    private function listUpdatableHoras($horas) {
        $remHoras = [];
        $this->ids['horas'] = [];
        $this->ids['horas'] = array_merge(array_column($horas, 'id_server'), $this->ids['horas']);        

        foreach ($horas as $h) {
            if ($this->getCount("horas", $h['id_server'], $h['updt_time']) == 0) {
                $u = R::getRow("SELECT ? as id, id as id_server,quantidade,hora_inicial,hora_termino,dta,tipo_hora,updt_time from horas where id = ? limit 1", [$h['id'], $h['id_server']]);
                if ($u === null) {
                    //passa a id da row como removível
                    array_push($remHoras, $h['id_server']);
                } else {
                    //adiciona $u como item atualizavel
                    array_push($this->update_horas, $u);
                }
            }
        }

        $this->rem['horas'] = $remHoras;        
    }

    private function listUpdatableSalarios($salarios) {
        $remSalarios = [];
        $this->ids['salarios'] = [];
        $this->ids['salarios'] = array_merge(array_column($salarios, 'id_server'), $this->ids['salarios']);

        foreach ($salarios as $s) {
            if ($this->getCount("salarios", $s['id_server'], $s['updt_time']) == 0) {
                $u = R::getRow("SELECT ? as id, id as id_server,valorsalario,if(status = 1, 'true', 'false') as status,vigencia,updt_time from salarios where id = ?", [$s['id'], $s['id_server']]);
                if ($u === null) {
                    array_push($remSalarios, $s['id_server']);
                } else {
                    array_push($this->update_salarios, $u);
                }
            }
        }

        $this->rem['salarios'] = $remSalarios;
    }

    private function listUpdatablePorcentagens($porcentagens) {
        $remPorc = [];
        $this->ids['porcentagens'] = [];
        $this->ids['porcentagens'] = array_merge(array_column($porcentagens, 'id_server'), $this->ids['porcentagens']);
        
        foreach ($porcentagens as $p) {
            if ($this->getCount("porcentagemdiferenciada", $p['id_server'], $p['updt_time']) == 0) {
                $u = R::getRow("SELECT ? as id, id as id_server,dia_semana,porcadicional,updt_time from porcentagemdiferenciada where id = ?", [$p['id'], $p['id_server']]);
                if ($u === null) {
                    array_push($remPorc, $p['id_server']);
                } else {
                    array_push($this->update_porcentagem, $u);
                }
            }
        }
        
        $this->rem['porcentagens'] = $remPorc;
    }

    private function listUpdatableEmpregos($empregos) {
        $remEmpregos = [];
        $this->ids['empregos'] = [];
        $this->ids['empregos'] = array_merge(array_column($empregos, 'id_server'), $this->ids['empregos']);
        
        foreach ($empregos as $e) {
            if ($this->getCount("empregos", $e['id_server'], $e['updt_time']) == 0) {
                $u = R::getRow("id as id_server,dia_fechamento,porc_normal,porc_feriados,nome_emprego,horario_saida,carga_horaria,banco_horas,notificacoes,updt_time from empregos where id = ?", [$e['id'], $e['id_server']]);
                if($u === null){
                    array_push($remEmpregos, $e['id_server']);
                }else{
                    array_push($this->update_empregos, $u);
                }
            }
        }
        
        $this->rem['empregos'] = $remEmpregos;        
    }
}
