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

    $query = "SELECT * FROM tbl_dlc WHERE (SELECT count(id_map) FROM tbl_map WHERE map_dlc_id = id_dlc) > 0";
    $select = $pdo->prepare($query);
    $select->execute();
    $dlcs = $select->fetchAll(PDO::FETCH_ASSOC);

    $dlc_dropdown = '<select id="dlc_dropdown" class="uk-select uk-width-1-1" name="dlc">';
    foreach ($dlcs as $dlc) {
        $dlc_dropdown .= '<option value="' . $dlc['id_dlc'] . '">' . $dlc['dlc_name'] . '</option>';
    }
    $dlc_dropdown .= '</select>';

    $query = "SELECT * FROM tbl_difficulty";
    $select = $pdo->prepare($query);
    $select->execute();
    $difficulties = $select->fetchAll(PDO::FETCH_ASSOC);

    $difficulty_dropdown = '<select class="uk-select uk-width-1-1" name="run[difficulty_id]">';
    foreach ($difficulties as $difficulty) {
        $difficulty_dropdown .= '<option value="' . $difficulty['id_difficulty'] . '" ' . ($difficulty['dif_name'] == default_difficulty ? 'selected' : '') . '>' . $difficulty['dif_name'] . '</option>';
    }
    $difficulty_dropdown .= '</select>';

	$content .= '<div class="uk-width-1-1">
		<div class="uk-card uk-card-default">
			<div class="uk-card-header">
				<h3 class="uk-card-title">Add a run</h3>
			</div>
			<div class="uk-card-body">
				<form class="uk-form uk-form-stacked" action="index.php?seite=navigation" method="post">
                    <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                        <div class="uk-width-1-2">
                            <label>DLC</label>
                            ' . $dlc_dropdown . '
                        </div>
                        <div class="uk-width-1-2">
                            <label>Map</label>
                            <select id="map_dropdown" class="uk-select uk-width-1-1" name="run[map_id]"></select>
                        </div>
                        <div class="uk-width-1-3">
                            <label>Grimoires</label>
                            <select class="uk-select uk-width-1-1" name="pro[grimoire_dice]">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                            </select>
                        </div>
                        <div class="uk-width-1-3">
                            <label>Tomes</label>
                            <select class="uk-select uk-width-1-1" name="pro[tome_dice]">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                            </select>
                        </div>
                        <div class="uk-width-1-3">
                            <label>Extra Dice</label>
                            <select class="uk-select uk-width-1-1" name="pro[extra_dice]">
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                            </select>
                        </div>
                        <div class="uk-width-1-3">
                            <label>Difficulty</label>
                            ' . $difficulty_dropdown . '
                        </div>
                        <div class="uk-width-1-2">
                            <label>Notes</label>
                            <textarea class="uk-width-1-1" name="run[notes]"></textarea>
                        </div>
                        <div class="uk-width-1-1">
                            <input type="hidden" name="task" value="add">
                            <input class="uk-button" type="submit" name="submit" value="Add">
                        </div>
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