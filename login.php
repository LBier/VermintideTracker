<?php

$task = get_request("task");

if (!empty($task)) {
    switch ($task) {
        case 'login':
            if (!empty($_POST['name']) && !empty($_POST['password'])) {
                $query = "SELECT * FROM tbl_user WHERE user_xDelete = 0 AND user_name = :user_name";
                $select = $pdo->prepare($query);
                $select->execute(array('user_name' => $_POST['name']));
                $user = $select->fetchObject();

                if ($user !== false) {
                    $verify = password_verify($_POST['password'], $user->user_password);

                    if ($verify === true) {
                        $query = "UPDATE tbl_user SET user_last_login = CURRENT_TIMESTAMP WHERE id_user = :id_user";
                        $update = $pdo->prepare($query);
                        $update->execute(array('id_user' => $user->id_user));

                        $_SESSION['user']['id'] = $user->id_user;
                        $_SESSION['user']['name'] = $user->user_name;
                        header('Location: index.php');
                        exit;
                    } else {
                        $result_text = "Wrong username or password.";
                    }
                } else {
                    // mask not finding a database entry
                    usleep(250000);
                    $result_text = "Wrong username or password.";
                }
            } else {
                $result_text = "Enter your username and password.";
            }
            break;
        case 'register':
            if (true) {
                $inputs = array();
                $inputs['user_name'] = $_POST['name'];
                $inputs['user_email'] = $_POST['email'];
                $inputs['user_password'] = password_hash($_POST['password'], PASSWORD_BCRYPT, array('cost' => 12));

                $query = "INSERT INTO tbl_user (user_name, user_password, user_email) VALUES (:user_name, :user_password, :user_email)";
                $insert = $pdo->prepare($query);
                $result = $insert->execute($inputs);

                if ($result === true) {
                    $result_text = "Registration successful.";
                }
            }
            break;
    }
}

$content = '<div id="block" class="uk-block uk-block-muted uk-align-center">
    <div id="result_text" class="uk-alert' . (empty($result_text) ? ' uk-hidden' : '') . '" data-uk-alert>
        <a href="" class="uk-alert-close uk-close"></a>
        <p>' . $result_text . '</p>
    </div>';

if ($page == "register") {
    $content .= '<div class="body">
        <h2>Register</h2>
        <form id="registration_form" class="uk-form" action="index.php" method="post">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-1-2">
                    <label for="email">Email</label>
                </div>
                <div class="uk-width-1-2">
                    <input id="email" type="email" name="email" required value="">
                </div>
                <div class="uk-width-1-2">
                    <label for="name">Username</label>
                </div>
                <div class="uk-width-1-2">
                    <input id="name" type="text" name="name" required value="">
                </div>
                <div class="uk-width-1-2">
                    <label for="password">Password</label>
                </div>
                <div class="uk-width-1-2">
                    <input id="password" type="password" name="password" required value="">
                </div>
                <div class="uk-width-1-2">
                    <label for="confirm_password">Confirm Password</label>
                </div>
                <div class="uk-width-1-2">
                    <input id="confirm_password" type="password" name="confirm_password" required value="">
                </div>
                <div class="uk-width-1-1">
                    <input type="hidden" name="task" value="register">
                    <input id="register" class="uk-button" type="submit" name="submit" value="Register">
                </div>
                <div class="uk-width-1-1">
                    <a class="uk-button" href="index.php">Back</a>
                </div>
            </div>
        </form>
    </div>';
} else {
    $content .= '<div class="body">
        <h2>Login</h2>
        <form id="login_form" class="uk-form" action="index.php" method="post">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-1-2">
                    <label for="name">Username</label>
                </div>
                <div class="uk-width-1-2">
                    <input id="name" type="text" name="name" required value="' . get_request("name") . '">
                </div>
                <div class="uk-width-1-2">
                    <label for="password">Password</label>
                </div>
                <div class="uk-width-1-2">
                    <input id="password" type="password" name="password" required value="' . get_request("password") . '">
                </div>
                <div class="uk-width-1-1">
                    <input type="hidden" name="task" value="login">
                    <input id="login" class="uk-button uk-align-center" type="submit" name="submit" value="Login">
                </div>
                <div class="uk-width-1-1">
                    <a class="uk-button" href="index.php?page=register">Create new account</a>
                </div>
            </div>
        </form>
    </div>';
}

$content .= '</div>';

echo $content;