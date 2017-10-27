<?php

require '../incl_config.php';

$return = "FAIL";

if (isset($_POST['id_run'])) {
    $delete = $pdo->prepare("DELETE FROM tbl_run WHERE id_run = :id_run");
    $result = $delete->execute(array("id_run" => $_POST['id_run']));

    if ($result === true && $delete->rowCount() > 0) {
        $return = "OK";
    }
}

echo $return;