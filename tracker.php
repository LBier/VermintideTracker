<?php

$id_run = get_request("id_run");
$isset_id = isset($id_run);

if (!empty($task)) {
	switch ($task) {
		case "add":
			if (!empty($_POST['submit']) && $_POST['submit'] == 'Add') {
			    // get parameter
                $cata_prob_red_on_6 = (int)get_parameter("SELECT par_value FROM tbl_parameter WHERE par_name = 'cata_prob_red_on_6'", "par_value");
                $pro_extra_grimoire_dice = 0;
                if (!empty($_POST['mod'])) {
                    foreach ($_POST['mod'] as $id_mod => $mod_extra_grimoire_dice) {
                        $pro_extra_grimoire_dice += (int)$mod_extra_grimoire_dice;
                    }
                }

			    // get probability
                $pro_grimoire_dice = (int)$_POST['pro']['grimoire_dice'] + $pro_extra_grimoire_dice;
                $dice_sum = $pro_grimoire_dice;
                $pro_tome_dice = 0;
                for ($i = 0; $i < (int)$_POST['pro']['tome_dice']; $i++) {
                    if ($dice_sum < 7) {
                        $pro_tome_dice += 1;
                        $dice_sum += 1;
                    }
                }
                $pro_extra_dice = 0;
                for ($i = 0; $i < (int)$_POST['pro']['extra_dice']; $i++) {
                    if ($dice_sum < 7) {
                        $pro_extra_dice += 1;
                        $dice_sum += 1;
                    }
                }
                $pro_normal_dice = 7 - ($pro_grimoire_dice + $pro_tome_dice + $pro_extra_dice);
//                echo $pro_grimoire_dice . "g" . $pro_tome_dice . "t" . $pro_extra_dice . "e" . $pro_normal_dice . "n";

                $query = "SELECT * FROM tbl_probability WHERE pro_grimoire_dice = :pro_grimoire_dice AND pro_tome_dice = :pro_tome_dice AND pro_extra_dice = :pro_extra_dice AND pro_normal_dice = :pro_normal_dice";
                $select = $pdo->prepare($query);
                $select->execute(array("pro_grimoire_dice" => $pro_grimoire_dice, "pro_tome_dice" => $pro_tome_dice, "pro_extra_dice" => $pro_extra_dice, "pro_normal_dice" => $pro_normal_dice));
                $probability = $select->fetchObject();

                if ($probability !== false) {
                    // prepare inputs
                    $inputs = array();
                    $inputs['run_map_id'] = (int)$_POST['run']['map_id'];
                    $inputs['run_difficulty_id'] = (int)$_POST['run']['difficulty_id'];
                    $inputs['run_probability_id'] = (int)$probability->id_probability;
                    $inputs['run_duration'] = (int)$_POST['run']['duration'];
                    switch ($inputs['run_difficulty_id']) {
                        case 5:
                            $inputs['run_probability_red'] = round((float)$probability->pro_probability_seven + ((float)$probability->pro_probability_six * $cata_prob_red_on_6 / 100), 2);
                            break;
                        case 4:
                            $inputs['run_probability_red'] = round((float)$probability->pro_probability_seven, 2);
                            break;
                        default:
                            $inputs['run_probability_red'] = 0;
                    }
                    $inputs['run_xRed'] = isset($_POST['run']['xRed']) ? 1 : 0;
                    $inputs['run_notes'] = empty($_POST['run']['notes']) ? null : $_POST['run']['notes'];

                    // save run
                    $query = "INSERT INTO tbl_run (run_map_id, run_difficulty_id, run_probability_id, run_duration, run_probability_red, run_xRed, run_notes) ";
                    $query .= "VALUES (:run_map_id, :run_difficulty_id, :run_probability_id, :run_duration, :run_probability_red, :run_xRed, :run_notes)";
                    $insert = $pdo->prepare($query);
                    $result = $insert->execute($inputs);

                    if ($result === true) {
                        $id_run = $pdo->lastInsertId();
                        if (!empty($_POST['mod'])) {
                            foreach ($_POST['mod'] as $id_mod => $mod_extra_grimoire_dice) {
                                $insert = $pdo->prepare("INSERT INTO tbl_run_mod (rm_run_id, rm_mod_id) VALUES (:rm_run_id, :rm_mod_id)");
                                $insert->execute(array("rm_run_id" => $id_run, "rm_mod_id" => $id_mod));
                            }
                        }

                        $result_text .= "Run has been saved";
                        header("Location: index.php?result_text=" . $result_text);
                    } else {
                        $result_text .= "Error saving run";
                    }
                } else {
                    $result_text .= "Flawed Inputs!";
                }
			}
			break;
		case "delete":
			if ($isset_id) {
				$delete = $pdo->prepare("DELETE FROM tbl_run WHERE id_run = :id_run");
				$result = $delete->execute(array("id_run" => $id_run));
				
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
		<div class="uk-alert" data-uk-alert>
			<a href="" class="uk-alert-close uk-close"></a>
            <p>' . $result_text . '</p>
		</div>
	</div>';
} else {
	$content = '';
}


if (isset($task) && $task == "add") {

    $dlcs = select("SELECT * FROM tbl_dlc WHERE (SELECT count(id_map) FROM tbl_map WHERE map_dlc_id = id_dlc) > 0");
    $dlc_dropdown = '<select id="dlc_dropdown" class="uk-select uk-width-1-1" name="dlc">';
    foreach ($dlcs as $dlc) {
        $dlc_dropdown .= '<option value="' . $dlc['id_dlc'] . '">' . $dlc['dlc_name'] . '</option>';
    }
    $dlc_dropdown .= '</select>';

    $difficulties = select("SELECT * FROM tbl_difficulty");
    $difficulty_dropdown = '<select id="dif_dropdown" class="uk-select uk-width-1-1" name="run[difficulty_id]">';
    foreach ($difficulties as $difficulty) {
        $difficulty_dropdown .= '<option value="' . $difficulty['id_difficulty'] . '" ' . ($difficulty['dif_name'] == default_difficulty ? 'selected' : '') . '>' . $difficulty['dif_name'] . '</option>';
    }
    $difficulty_dropdown .= '</select>';

    $mods = select("SELECT * FROM tbl_mod");
    $mods_checkboxes = '<div class="uk-grid uk-grid-medium uk-grid-width-1-1" data-uk-grid-margin>';
    foreach ($mods as $mod) {
        $mods_checkboxes .= '<div class="uk-width-1-3">';
        $mods_checkboxes .= '<input id="mod_' . $mod['mod_name'] . '" type="checkbox" name="mod[' . $mod['id_mod'] . ']" value="' . $mod['mod_extra_grimoire_dice'] . '">';
        $mods_checkboxes .= '<label for="mod_' . $mod['mod_name'] . '"> ' . $mod['mod_description'] . '</label>';
        $mods_checkboxes .= '</div>';
    }
    $mods_checkboxes .= '</div>';

	$content .= '<div class="uk-width-1-1">
        <div class="header">
            <h2>Add a run</h2>
        </div>
        <div class="body">
            <form class="uk-form uk-form-stacked" action="index.php" method="post">
                <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                    <div class="uk-width-1-2">
                        <label>DLC</label>
                        ' . $dlc_dropdown . '
                    </div>
                    <div class="uk-width-1-2">
                        <label>Map</label>
                        <select id="map_dropdown" class="uk-select uk-width-1-1" name="run[map_id]">
                        </select>
                    </div>
                    <div class="uk-width-1-3">
                        <label>Difficulty</label>
                        ' . $difficulty_dropdown . '
                    </div>
                    <div class="uk-width-1-3">
                        <label>Duration (min)</label>
                        <input class="uk-input uk-width-1-1" type="number" name="run[duration]">
                    </div>
                    <div class="uk-width-1-3">
                        <input id="xRed" type="checkbox" name="run[xRed]">
                        <label for="xRed">Got Red Item</label>
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
                    <div class="uk-width-1-1">
                        <label>Mods</label>
                        ' . $mods_checkboxes . '
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
	</div>';
	
} else {
	
	// run list
	$query = "SELECT * FROM vw_run ORDER BY run_createDtTi DESC";
	$select = $pdo->prepare($query);
	$select->execute();
	$runs = $select->fetchAll(PDO::FETCH_ASSOC);
	
	if (!empty($runs)) {
		foreach ($runs as &$run) {
			$query = "SELECT * FROM tbl_run_mod as rm, tbl_mod as `mod` WHERE rm.rm_mod_id = `mod`.id_mod AND rm.rm_run_id = :rm_run_id";
			$select = $pdo->prepare($query);
			$select->execute(array("rm_run_id" => $run['id_run']));
			$run['mods'] = $select->fetchAll(PDO::FETCH_ASSOC);

			$run['rendered_mods'] = '';
			if (!empty($run['mods'])) {
				foreach ($run['mods'] as $mod) {
					$run['rendered_mods'] .= $mod['mod_description'] . '<br>';
				}
			}
		}
	}
//    dump($runs);
//    exit;

	$content .= '<div class="uk-width-1-1">
        <div class="header">
            <h2 class="uk-float-left">Run Overview</h2>
        </div>
        <div class="body">
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
                    <col>
                    <col>
                    <col width="5%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Difficulty</th>
                        <th>Mods</th>
                        <th>Map</th>
                        <th>Duration</th>
                        <th>Dice</th>
                        <th>red %</th>
                        <th>got a red</th>
                        <th>Notes</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
                    if (!empty($runs)) {
                        foreach ($runs as &$run) {
                            $content .= '<tr>
                                <td>' . $run['dif_name'] . '</td>
                                <td>' . $run['rendered_mods'] . '</td>
                                <td>' . $run['map_name'] . '</td>
                                <td>' . $run['run_duration'] . ' min</td>
                                <td>' . $run['pro_dice_string'] . '</td>
                                <td>' . $run['run_probability_red'] . '%</td>
                                <td>' . ($run['run_xRed'] == 1 ? 'Yes' : 'No') . '</td>
                                <td>' . $run['run_notes'] . '</td>
                                <td>' . date("d.m.Y H:i", strtotime($run['run_createDtTi'])) . '</td>
                                <td>
                                    <form action="index.php" method="post">
                                        <input type="hidden" name="task" value="delete">
                                        <input type="hidden" name="id_run" value="' . $run['id_run'] . '">
                                        <button class="uk-button-secondary" type="submit" title="Delete" onClick="return confirm(\'Delete run?\')"><i class="uk-icon-trash"></i></button>
                                    </form>
                                </td>
                            </tr>';
                        }
                    } else {
                        $content .= '<tr><td colspan="8">No runs available</td></tr>';
                    }
                $content .= '</tbody>
            </table>
        </div>
	</div>';
}

echo $content;