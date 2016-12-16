<?php


namespace controllers {

    include_once "./sync/PorcentagensInsert.php";
    include_once "./base/BaseDelete.php";


    class PorcentagemController {

        function __construct() {
            
        }

        function insert($id_client, $id_emprego, $json) {
            $ins = new InsertPorcDiff($id_client);
            return $ins->insert($id_emprego, $json);
        }

        function update($id_client, $id_emprego, $json) {
            
        }

        function delete($id_client, $id_emprego, $id) {
            $d = new BaseDelete("porcentagemdiferenciada", $id_client, $id_emprego);
            echo $d->deleteById($id);
        }

    }

}
