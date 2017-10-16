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
	</body>
</html>