<?php

class BaseQuerys{
    
    function __construct() {        
    }
    
    public $sqlEmpregos = "SELECT id as id_server,dia_fechamento,porc_normal,porc_feriados,nome_emprego,horario_saida,carga_horaria,banco_horas,notificacoes,updt_time from empregos";
    public $sqlHoras = "SELECT id as id_server,quantidade,hora_inicial,hora_termino,dta,tipo_hora,updt_time from horas";
    public $sqlSalarios = "SELECT id as id_server,valorsalario,if(status = 1, 'true', 'false') as status,vigencia,updt_time from salarios";
    public $sqlPorcentagem = "SELECT id as id_server,dia_semana,porcadicional,updt_time from porcentagemdiferenciada";
}