<?

$result_text = "";

if (!empty($task)) {
	switch ($task) {
		case "add":
			if (!empty($_POST['run']) && $_POST['submit'] == 'Add') {
				
				$insert = $pdo->prepare("INSERT INTO tbl_navigation (
					na_pg_id,
					na_xActive,
					na_navigation,
					na_text,
					na_link)
					VALUES (?, ?, ?, ?, ?)");
				$result = $insert->execute(array(
					(!empty($_POST['navigation']['pg_id']) ? $_POST['navigation']['pg_id'] : NULL),
					(isset($_POST['navigation']['xActive']) ? 1 : 0),
					$navigation,
					$_POST['navigation']['text'],
					$_POST['navigation']['link']));
				
				if ($result === true) {
					$id_navigation = $pdo->lastInsertId();
					$isset_id = true;
					$result_text .= "Run has been saved.";
				} else {
					$result_text .= "Error saving run.";
				}
				
			}
			break;
		case "delete":
			if ($isset_id) {
				$delete = $pdo->prepare("DELETE FROM tbl_run WHERE id_run = ?");
				$result = $delete->execute(array($id_run));
				
				if ($result === true) {
					$result_text .= "Run has been deleted.";
				} else {
					$result_text .= "Error deleting run.";
				}
			}
			break;
	}
}

// Feld mit dem result text erstellen (oder auch nicht)
if (!empty($result_text)) {
	$content = '<div class="uk-width-1-1">
		<div class="uk-card uk-card-default">
			<div id="result_text" class="uk-card-header">
				<p>' . $result_text . '</p>
			</div>
		</div>
	</div>';
} else {
	$content = '';
}


if (isset($task) && $task == "add") {
	
	$content .= '<div class="uk-width-1-1">
		<div class="uk-card uk-card-default">
			<div class="uk-card-header">
				<h3 class="uk-card-title">Add a run</h3>
			</div>
			<div class="uk-card-body">
				<form class="uk-form-stacked uk-grid-small" action="index.php?seite=navigation" method="post" uk-grid>
					<div class="uk-width-1-2">
						<label>Seite</label>
						' . get_page_select("navigation[pg_id]", return_var($navigation, "na_pg_id"), true) . '
					</div>
					<div class="uk-width-1-2">
						<div style="margin-top: 30px;">
							<input id="xActive" class="uk-checkbox" type="checkbox" name="navigation[xActive]" ' . (return_var($navigation, "na_xActive") == 1 ? 'checked' : '') . ' value="1">
							<label for="xActive">Aktiviert</label>
						</div>
					</div>
					<div class="uk-width-1-1">
						<label>Navigation</label>
						<input class="uk-input uk-width-1-1" name="navigation[navigation]" value="' . return_var($navigation, "na_navigation") . '">
					</div>
					<div class="uk-width-1-1">
						<label>Text</label>
						<input class="uk-input uk-width-1-1" name="navigation[text]" value="' . return_var($navigation, "na_text") . '">
					</div>
					<div class="uk-width-1-1">
						<label>Link</label>
						<input class="uk-input uk-width-1-1" name="navigation[link]" value="' . return_var($navigation, "na_link") . '">
					</div>
					<div class="uk-width-1-1">
						<input type="hidden" name="task" value="add">
						<input class="uk-button" type="submit" name="submit" value="Add">
					</div>
				</form>
			</div>
		</div>
	</div>';
	
} else {
	
	// run list
	$query = "SELECT * FROM vw_run";
	$select = $pdo->prepare($query);
	$select->execute();
	$runs = $select->fetchAll(PDO::FETCH_ASSOC);
	
	if (!empty($runs)) {
		foreach ($runs as &$run) {
			$query = "SELECT * FROM tbl_run_mod as rm, tbl_mod as `mod` WHERE rm.rm_mod_id = `mod`.id_mod AND rm.rm_run_id = ?";
			$select = $pdo->prepare($query);
			$select->execute(array($run['id_run']));
			$run['mods'] = $select->fetchAll(PDO::FETCH_ASSOC);
			
			$run['rendered_mods'] = '';
			if (!empty($run['mods'])) {
				foreach ($run['mods'] as $mod) {
					$run['rendered_mods'] .= $mod['mod_description'] . '<br>';
				}
			}
		}
	}
	// dump($runs);
	// exit;
	
	$content .= '<div class="uk-width-1-1">
		<div class="uk-card uk-card-default">
			<div class="uk-card-header">
				<h3 class="uk-card-title uk-float-left">Run Overview</h3>
			</div>
			<div class="uk-card-body">
				<form class="uk-float-right" action="index.php" method="post">
					<input type="hidden" name="task" value="add">
					<input class="uk-button" type="submit" value="Add run">
				</form>
				<table class="uk-table uk-table-striped">
					<colgroup>
						<col>
						<col>
						<col>
						<col>
						<col>
						<col>
						<col>
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>Difficulty</th>
							<th>Mods</th>
							<th>Map</th>
							<th>Length</th>
							<th>Dice</th>
							<th>red %</th>
							<th>Date</th>
							<th></th>
						</tr>
					</thead>
					<tbody>';
						if (!empty($runs)) {
							foreach ($runs as $run) {
								$content .= '<tr>
									<td>' . $run['dif_name'] . '</td>
									<td>' . $run['rendered_mods'] . '</td>
									<td>' . $run['map_name'] . '</td>
									<td>' . $run['run_length'] . ' min</td>
									<td>' . $run['pro_dice_string'] . '</td>
									<td>' . $run['run_probability_red'] . '</td>
									<td>' . date("d.m.Y H:i", strtotime($run['run_createDtTi'])) . '</td>
									<td>
										<ul class="uk-iconnav uk-flex-center">
											<li>
												<form action="index.php" method="post">
													<input type="hidden" name="task" value="delete">
													<input type="hidden" name="id_run" value="' . $run['id_run'] . '">
													<button type="submit" title="Delete" onClick="return confirm(\'Delete run?\')" uk-icon="icon:trash"></button>
												</form>
											</li>
										</ul>
									</td>
								</tr>';
							}
						} else {
							$content .= '<tr><td>No runs available</td></tr>';
						}
					$content .= '</tbody>
				</table>
			</div>
		</div>
	</div>';
}

echo $content;

?>