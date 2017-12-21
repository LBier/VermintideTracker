<?php

require '../incl_config.php';

$error = array();

if (isset($_POST) && !empty($_POST['email']) && !empty($_POST['name']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && $_POST['task'] == 'register') {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // check if email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $error['email'] = "Email is not valid.";
    }

    // check if name is used
    $query = "SELECT id_user FROM tbl_user WHERE user_name = :user_name";
    $select = $pdo->prepare($query);
    $select->execute(array("user_name" => $name));
    if ($select->rowCount() > 0) {
        $error['name'] = "Name is already used.";
    }

    // check for password security
    if (mb_strlen($password) < 4) {
        $error['password'] = "Password is too short.";
    }

    // check if password and confirm match
    if ($password !== $confirm_password) {
        $error['confirm_password'] = "Passwords don't match";
    }
}

if (empty($error)) {
    echo "OK";
} else {
    echo serialize($error);
}