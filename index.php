<?php

require 'incl_config.php';
require 'incl_functions.php';

session_start();

$page = get_request("page", "tracker");
$result_text = get_request("result_text", "");

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Vermintide Stat Tracker</title>
        <meta charset="utf-8">
        <meta name="author" content="Lukas Bier">
		<link rel="stylesheet" href="template/css/styles.css">
		<link rel="stylesheet" href="template/css/uikit.min.css">
        <script src="template/js/jquery-3.2.1.min.js"></script>
        <script src="template/js/uikit.min.js"></script>
        <script src="template/js/functions.js"></script>
	</head>
	<body>
    <?php
    if (isset($_SESSION['user']['id']) === true) {

        if (isset($_GET['logout']) && $_GET['logout'] == "1") {
            unset($_SESSION['user']);
            redirect(HTTP_HOST_URL . '/index.php');
        }
    ?>
        <div id="navigation" class="uk-margin-top">
            <div class="uk-tab-center">
                <ul class="uk-tab">
                    <?php
                    foreach ($navigation as $navigation_page => $navigation_text) {
                        echo '<li' . ($page == $navigation_page ? ' class="uk-active"' : '') . '><a href="index.php?page=' . $navigation_page . '">' . $navigation_text . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div id="content">
            <div class="uk-grid uk-animation-fade">
                <?php
                    switch ($page) {
                        case "statistics":
                            include 'statistics.php';
                            break;
                        default:
                            include 'tracker.php';
                    }
                ?>
            </div>
        </div>
        <div id="footer">
            Logged in as: <?php echo $_SESSION['user']['name'];?>
            <a href="index.php?logout=1" class="uk-button">Logout</a>
        </div>
    <?php
    } else {
        include 'login.php';
    }
    ?>
        <script>
            $( document ).ready(function() {

                $('.delete-run').click(function () {
                    if (confirm('Delete run?')) {
                        var id_run = $(this).data("id_run");
                        $.ajax({
                            type: "POST",
                            url: "ajax/delete_run.php",
                            data: {"id_run": id_run},
                            success: function(data) {
                                if (data === "OK") {
                                    $('#run-' + id_run).remove();
                                    alert("Run has been deleted.");
                                } else {
                                    alert("Error deleting run");
                                }
                            }
                        });
                    }
                });

                var dlc_dropdown = $('#dlc_dropdown');
                var first_dlc = dlc_dropdown.find("option:first-child").val();

                // the map dropdown gets filled based on the selected DLC
                get_map_options(first_dlc);
                dlc_dropdown.on("change", function(e) {
                    var dlc = $(this).val();
                    get_map_options(dlc);
                });

                // the grim and tome dropdowns get filled based on the selected map
                $('#map_dropdown').on("change", function(e) {
                    var map = $(this).val();
                    get_book_options(map, "grimoires");
                    get_book_options(map, "tomes");
                });

                // if the selected difficulty is not Cataclysm, the deathiwsh checkbox is disabled
                check_deathwish();
                $('#dif_dropdown').on("change", function(e) {
                    check_deathwish();
                });

                // check registration inputs
                $('#register').on("click", function(e) {
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "ajax/check_registration.php",
                        data: $("#registration_form").serialize(),
                        success: function(data) {
                            if (data !== "OK") {
                                alert("Registration failed");
                                e.preventDefault();
                            }
                        },
                        error: function(error) {
                            e.preventDefault();
                        }
                    });
                });

            });
        </script>
	</body>
</html>