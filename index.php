<?

require 'incl_config.php';
require 'incl_functions.php';

// $page = get_request("page");
$task = get_request("task");
$id_run = get_request("id_run");

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
	</head>
	<body>
		<div id="content">
			<div class="uk-grid-match uk-animation-fade" uk-grid>
				<?
				include "tracker.php";
				?>
			</div>
		</div>
        <script>
            $( document ).ready(function() {

                var first_dlc = $('#dlc_dropdown').find("option:first-child").val();
                get_map_options(first_dlc);

                $('#dlc_dropdown').on("change", function(e) {
                    var dlc = $(this).val();
                    get_map_options(dlc);
                });

            });

            function get_map_options(dlc) {
                $.ajax({
                    type: "POST",
                    url: "get_map_options.php",
                    data: {"dlc": dlc},
                    success: function(data) {
                        $('#map_dropdown').html(data);
                    }
                });
            }
        </script>
	</body>
</html>