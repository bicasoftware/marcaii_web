<?php


namespace controllers {

    include_once "./sync/SalariosInsert.php";
    include_once "./base/BaseDelete.php";

    class SalariosController {

        function __construct() {
            
        }

        function insert($id_client, $id_emprego, $json) {
            $ins = new InsertSalario($id_client);
            return $ins->insert($id_emprego, $json);
        }

        function update($id_client, $id_emprego, $json) {
            
        }

        function delete($id_client, $id_emprego, $id) {
            $d = new BaseDelete("salarios", $id_client, $id_emprego);
            echo $d->deleteById($id);
        }
    }
}
