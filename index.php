<?php

require 'incl_config.php';
require 'incl_functions.php';

// $page = get_request("page");
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
		<div id="content">
			<div class="uk-grid uk-animation-fade">
				<?php
				include "tracker.php";
				?>
			</div>
		</div>
        <script>
            $( document ).ready(function() {

                var dlc_dropdown = $('#dlc_dropdown');
                var first_dlc = dlc_dropdown.find("option:first-child").val();

                get_map_options(first_dlc);
                dlc_dropdown.on("change", function(e) {
                    var dlc = $(this).val();
                    get_map_options(dlc);
                });

                $('#map_dropdown').on("change", function(e) {
                    var map = $(this).val();
                    get_book_options(map, "grimoires");
                    get_book_options(map, "tomes");
                });

                check_deathwish();
                $('#dif_dropdown').on("change", function(e) {
                    check_deathwish();
                });

            });
        </script>
	</body>
</html>