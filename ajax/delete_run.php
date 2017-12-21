<?php

require '../incl_config.php';

session_start();

$return = "FAIL";

if (isset($_POST['id_run']) && isset($_SESSION['user']['id'])) {
    $delete = $pdo->prepare("DELETE FROM tbl_run WHERE id_run = :id_run AND run_user_id = :run_user_id");
    $result = $delete->execute(array("id_run" => $_POST['id_run'], "run_user_id" => $_SESSION['user']['id']));

    if ($result === true && $delete->rowCount() > 0) {
        $return = "OK";
    }
}

echo $return;