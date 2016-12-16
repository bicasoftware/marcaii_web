<?php

include_once './base/BaseQuerys.php';

class FullResync {

    private $empregos = [];
    private $bq;

    function __construct($id_user) {
        $this->bq = new BaseQuerys($id_user);
        $this->empregos = R::getAll($this->bq->sqlEmpregos.' where id_user = ?', [$id_user]);
    }

    public function getSyncData() {
        $dados = [];
        foreach ($this->empregos as $e) {
            $id = $e['id_server'];

            $e['horas'] = R::getAll($this->bq->sqlHoras." where id_emprego = ?", [$id]);
            $e['salarios'] = R::getAll($this->bq->sqlSalarios." where id_emprego = ?", [$id]);
            $e['porcentagemdiferenciada'] = R::getAll($this->bq->sqlPorcentagem." where id_emprego = ?", [$id]);
            array_push($dados, $e);
        }

        return $dados;
    }

}

