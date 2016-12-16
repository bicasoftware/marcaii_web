<?php

//include("JsonBuilder.php");
include_once("conn.php");

class BaseDelete {

    private $table;
    private $id_emprego;
    private $id_user;

    function __construct($table, $id_user, $id_emprego) {
        $this->id_user = $id_user;
        $this->id_emprego = $id_emprego;
        $this->table = $table;
    }
    
    function deleteById($id){
        $ret = ["status"=>0, "ex"=>"ok"];
        
        try{
            R::exec("delete from ".$this->table." where id_user = ? and id_emprego = ? and id = ?", [$this->id_user, $this->id_emprego, $id]);
        } catch (Exception $ex) {
            error_log($ex);
            $ret = ["status"=>1, "ex"=>$ex];
        }
        return $ret;
    }

}
