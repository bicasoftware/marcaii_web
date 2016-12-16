<?php

include_once './base/BaseQuerys.php';

class NewRecordHelper {

    private $id_user = -1;
    private $ids = [];
    private $bq;

    function __construct($id_user, $ids) {
        $this->id_user = $id_user;
        $this->ids = $ids;
        $this->bq = new BaseQuerys($id_user);
    }

    public function getNewRecords() {
        return $this->listaNovosEmpregos();
    }

    private function listaNovosEmpregos() {
        $data = [];
        $empregos = [];
        $single = [];

        //lista empregos não salvos no cliente
        $novos_empregos = $this->listEmpregos($this->ids['empregos']);

        //se existem novos empregos salvos, gera nó {empregos} e lista todas as horas, salarios e porcentagens       
        if (count($novos_empregos) > 0) {
            foreach ($novos_empregos as $emp) {
                $id = $emp['id'];
                $e['id_server'] = $emp['id'];
                $e['dia_fechamento'] = $emp['dia_fechamento'];
                $e['porc_normal'] = $emp['porc_normal'];
                $e['porc_feriados'] = $emp['porc_feriados'];
                $e['nome_emprego'] = $emp['nome_emprego'];
                $e['horario_saida'] = $emp['horario_saida'];
                $e['carga_horaria'] = $emp['carga_horaria'];
                $e['banco_horas'] = $emp['banco_horas'];
                $e['notificacoes'] = $emp['notificacoes'];
                $e['updt_time'] = $emp['updt_time'];

                $e['horas'] = R::getAll($this->bq->sqlHoras . " where id_emprego = ?", [$id]);
                $e['salarios'] = R::getAll($this->bq->sqlSalarios . " where id_emprego = ?", [$id]);
                $e['porcentagemdiferenciada'] = R::getAll($this->bq->sqlPorcentagem . " where id_emprego = ?", [$id]);

                //adiciona novo emprego no array
                array_push($empregos, $e);
                //remove a id do emprego do array
                $this->ids['empregos'] = array_diff($this->ids['empregos'], $id);
                //array_column extrai um array de uma coluna em um array multidimensional
                //extrai a id_server e filtra com array_diff nas ids[]
                $this->ids['horas'] = array_diff($this->ids['horas'], array_column($e['horas'], 'id_server'));
                $this->ids['salarios'] = array_diff($this->ids['salarios'], array_column($e['salarios'], 'id_server'));
                $this->ids['porcentagem'] = array_diff($this->ids['porcentagem'], array_column($e['porcentagem'], 'id_server'));
            }
        }

        $emp = R::getAll("select id as id_server, updt_time from empregos where id_user = ?", [$this->id_user]);
        foreach ($emp as $row) {
            $sgl['horas'] = $this->listNotIn($this->bq->sqlHoras, $this->ids['horas'], $row['id_server']);
            $sgl['salarios'] = $this->listNotIn($this->bq->sqlSalarios, $this->ids['salarios'], $row['id_server']);
            
            $sgl['porcentagens'] = $this->listNotIn($this->bq->sqlPorcentagem, $this->ids['porcentagens'], $row['id_server']);
            
            if (count($sgl['horas']) > 0 || count($sgl['salarios']) > 0 || count($sgl['porcentagens']) > 0) {
                $sgl['id_server'] = $row['id_server'];
                $sgl['updt_time'] = $row['updt_time'];

                array_push($single, $sgl);
            }
        }

        $data['empregos'] = $empregos;
        $data['single'] = $single;

        return $data;
    }

    private function listNotIn($sql, $ids, $id_emprego) {
        
        //se a lista de id é vazia, retorna todos os itens da tabela, filtrando id_user e id_emprego;
        if (count($ids) == 0) {
            return R::getAll($sql . " where id_user = ? and id_emprego = ?",[$this->id_user, $id_emprego]);
            //return R::getAll($sql . ' limit 0');
        }else if(count($ids) == 1){
            //se existe um item na lista, retorna todas as ids exceto um item
            return R::getAll($sql . " where id_user = ".$this->id_user." and id_emprego = ".$id_emprego." and id != ?", $ids);
        }else {
            //se a lista tiver mais de 1 item, filtra id com not in(?,?)
            $query = $sql . " where id_user = ".$this->id_user." and id_emprego = ".$id_emprego." and id not in(" . R::genSlots($ids) . ")";
            return R::getAll($query, $ids);
        }
    }

    public function listEmpregos($ids) {        
        if (count($ids) == 0) {
            return R::getAll($this->bq->sqlEmpregos . ' limit 0');
        } else if (count($ids) == 1) {
            return R::getAll($this->bq->sqlEmpregos . " where id_user = ? and id != ?", [$this->id_user, $ids]);
        } else {
            return R::getAll($this->bq->sqlEmpregos . ' where id_user = ? and id not in(' . R::genSlots($ids) . ')', [$this->id_user, $ids]);
        }
    }

}
