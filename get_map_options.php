<?php

require 'incl_config.php';

$return = '';

if (isset($_POST['dlc'])) {
    $query = "SELECT * FROM tbl_map WHERE map_dlc_id = :map_dlc_id";
    $select = $pdo->prepare($query);
    $select->execute(array("map_dlc_id" => $_POST['dlc']));
    $maps = $select->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($maps)) {
        foreach ($maps as $map) {
            $return .= '<option value="' . $map['id_map'] . '">' . $map['map_name'] . '</option>';
        }
    }
}

echo $return;