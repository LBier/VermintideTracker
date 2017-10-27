<?php

require '../incl_config.php';

$return = '';

if (isset($_POST['map']) && isset($_POST['book_type'])) {
    $query = "SELECT * FROM tbl_map WHERE id_map = :id_map";
    $select = $pdo->prepare($query);
    $select->execute(array("id_map" => $_POST['map']));
    $result = $select->fetchAll(PDO::FETCH_ASSOC);

    $book_types = array("grimoires", "tomes");
    $key = array_search($_POST['book_type'], $book_types);

    if ($result !== false && $key !== false) {
        $book_count = $result[0]["map_" . $book_types[$key]];
        for ($i = 0; $i <= $book_count; $i++) {
            $return .= '<option>' . $i . '</option>';
        }
    }
}

echo $return;