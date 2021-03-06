<?php

$task = get_request("task");
$id_run = get_request("id_run");
$isset_id = isset($id_run);

// Since PDO doesn't support column names for preparing, the order has to be in $allowed_order. This way SQL-Injections are not possible
$allowed_order = array("hero_name", "dif_level", "map_name", "run_duration", "pro_dice_string", "run_probabilty_red", "rar_level", "run_createDtTi", "asc", "desc");

$key_order = array_search(get_request("order"), $allowed_order);
$key_direction = array_search(get_request("direction"), $allowed_order);

$order = $key_order !== false ? $allowed_order[$key_order] : DEFAULT_ORDER;
$direction = $key_direction !== false ? $allowed_order[$key_direction] : DEFAULT_DIRECTION;

if (!empty($task)) {
	switch ($task) {
		case "add":
			if (!empty($_POST['submit']) && $_POST['submit'] == 'Add') {
			    // get parameter
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
                    $inputs['run_hero_id'] = (int)$_POST['run']['hero_id'];
                    $inputs['run_map_id'] = (int)$_POST['run']['map_id'];
                    $inputs['run_difficulty_id'] = (int)$_POST['run']['difficulty_id'];
                    $inputs['run_probability_id'] = (int)$probability->id_probability;
                    $inputs['run_rarity_id'] = (int)$_POST['run']['rarity_id'];
                    $inputs['run_duration'] = (int)$_POST['run']['duration'];
                    switch ($inputs['run_difficulty_id']) {
                        case 5:
                            $cata_prob_red_on_6 = (int)get_parameter("SELECT par_value FROM tbl_parameter WHERE par_name = 'cata_prob_red_on_6'", "par_value");
                            $inputs['run_probability_red'] = round((float)$probability->pro_probability_seven + ((float)$probability->pro_probability_six * $cata_prob_red_on_6 / 100), 2);
                            break;
                        case 4:
                            $inputs['run_probability_red'] = round((float)$probability->pro_probability_seven, 2);
                            break;
                        default:
                            $inputs['run_probability_red'] = 0;
                    }
                    $inputs['run_notes'] = empty($_POST['run']['notes']) ? null : $_POST['run']['notes'];

                    // save run
                    $query = "INSERT INTO tbl_run (run_hero_id, run_map_id, run_difficulty_id, run_probability_id, run_rarity_id, run_duration, run_probability_red, run_notes) ";
                    $query .= "VALUES (:run_hero_id, :run_map_id, :run_difficulty_id, :run_probability_id, :run_rarity_id, :run_duration, :run_probability_red, :run_notes)";
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
                        exit;
                    } else {
                        $result_text .= "Error saving run";
                    }
                } else {
                    $result_text .= "Flawed Inputs!";
                }
			}
			break;
//		case "delete":
//			if ($isset_id) {
//				$delete = $pdo->prepare("DELETE FROM tbl_run WHERE id_run = :id_run");
//				$result = $delete->execute(array("id_run" => $id_run));
//
//				if ($result === true) {
//					$result_text .= "Run has been deleted.";
//				} else {
//					$result_text .= "Error deleting run.";
//				}
//			}
//			break;
	}
}

// Feld mit dem result text erstellen (oder auch nicht)
if (!empty($result_text)) {
	$content = '<div class="uk-width-1-1 uk-margin-bottom">
		<div class="uk-alert" data-uk-alert>
			<a href="" class="uk-alert-close uk-close"></a>
            <p>' . $result_text . '</p>
		</div>
	</div>';
} else {
	$content = '';
}


if (isset($task) && $task == "add") {

    $heroes = select("SELECT * FROM tbl_hero");
    $hero_dropdown = '<select id="hero_dropdown" class="uk-select uk-width-1-1" name="run[hero_id]">';
    foreach ($heroes as $hero) {
        $hero_dropdown .= '<option value="' . $hero['id_hero'] . '" ' . ($hero['hero_name'] == DEFAULT_HERO ? 'selected' : '') . '>' . $hero['hero_name'] . '</option>';
    }
    $hero_dropdown .= '</select>';

    $dlcs = select("SELECT * FROM tbl_dlc WHERE (SELECT count(id_map) FROM tbl_map WHERE map_dlc_id = id_dlc) > 0");
    $dlc_dropdown = '<select id="dlc_dropdown" class="uk-select uk-width-1-1" name="dlc">';
    foreach ($dlcs as $dlc) {
        $dlc_dropdown .= '<option value="' . $dlc['id_dlc'] . '">' . $dlc['dlc_name'] . '</option>';
    }
    $dlc_dropdown .= '</select>';

    $difficulties = select("SELECT * FROM tbl_difficulty");
    $difficulty_dropdown = '<select id="dif_dropdown" class="uk-select uk-width-1-1" name="run[difficulty_id]">';
    foreach ($difficulties as $difficulty) {
        $difficulty_dropdown .= '<option value="' . $difficulty['id_difficulty'] . '" ' . ($difficulty['dif_name'] == DEFAULT_DIFFICULTY ? 'selected' : '') . '>' . $difficulty['dif_name'] . '</option>';
    }
    $difficulty_dropdown .= '</select>';

    $rarities = select("SELECT * FROM tbl_rarity");
    $rarity_dropdown = '<select class="uk-select uk-width-1-1" name="run[rarity_id]">';
    foreach ($rarities as $rarity) {
        $rarity_dropdown .= '<option value="' . $rarity['id_rarity'] . '" ' . ($rarity['rar_name'] == DEFAULT_RARITY ? 'selected' : '') . '>' . $rarity['rar_color'] . '</option>';
    }
    $rarity_dropdown .= '</select>';

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
                    <div class="uk-width-1-3">
                        <label>Hero</label>
                        ' . $hero_dropdown . '
                    </div>
                    <div class="uk-width-1-3">
                        <label>DLC</label>
                        ' . $dlc_dropdown . '
                    </div>
                    <div class="uk-width-1-3">
                        <label>Map</label>
                        <select id="map_dropdown" class="uk-select uk-width-1-1" name="run[map_id]">
                        </select>
                    </div>
                    <div class="uk-width-1-3">
                        <label>Difficulty</label>
                        ' . $difficulty_dropdown . '
                    </div>
                    <div class="uk-width-1-3">
                        <label>Item Rarity</label>
                        ' . $rarity_dropdown . '
                    </div>
                    <div class="uk-width-1-3">
                        <label>Duration (min)</label>
                        <input class="uk-input uk-width-1-1" type="number" name="run[duration]">
                    </div>
                    <div class="uk-width-1-3">
                        <label>Grimoires</label>
                        <select id="grimoires_dropdown" class="uk-select uk-width-1-1" name="pro[grimoire_dice]">
                        </select>
                    </div>
                    <div class="uk-width-1-3">
                        <label>Tomes</label>
                        <select id="tomes_dropdown" class="uk-select uk-width-1-1" name="pro[tome_dice]">
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
	$query = "SELECT * FROM vw_run ORDER BY " . $order . " " . $direction;
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
                    <col width="10%">
                    <col>
                    <col width="5%">
                </colgroup>
                <thead>
                    <tr>
                        <th>' . get_sort_buttons("hero_name") . '</th>
                        <th>' . get_sort_buttons("dif_level") . '</th>
                        <th></th>
                        <th>' . get_sort_buttons("map_name") . '</th>
                        <th>' . get_sort_buttons("run_duration") . '</th>
                        <th>' . get_sort_buttons("pro_dice_string") . '</th>
                        <th>' . get_sort_buttons("run_probability_red") . '</th>
                        <th>' . get_sort_buttons("rar_level") . '</th>
                        <th></th>
                        <th>' . get_sort_buttons("run_createDtTi") . '</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Hero</th>
                        <th>Difficulty</th>
                        <th>Mods</th>
                        <th>Map</th>
                        <th>Duration</th>
                        <th title="Short form for the number of Grimoire, Tome, Extra and Normal Dice">Dice</th>
                        <th>red %</th>
                        <th>Item Rarity</th>
                        <th>Notes</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
                    if (!empty($runs)) {
                        foreach ($runs as &$run) {
                            $content .= '<tr id="run-' . $run['id_run'] . '">
                                <td>' . $run['hero_name'] . '</td>
                                <td>' . $run['dif_name'] . '</td>
                                <td>' . $run['rendered_mods'] . '</td>
                                <td>' . $run['map_name'] . '</td>
                                <td>' . $run['run_duration'] . ' min</td>
                                <td>' . $run['pro_dice_string'] . '</td>
                                <td>' . $run['run_probability_red'] . '%</td>
                                <td>' . $run['rar_color'] . '</td>
                                <td>';
                                if (strlen($run['run_notes']) > 60) {
                                    $content .= '<span title="' . $run['run_notes'] . '">' . mb_substr($run['run_notes'], 0, 60) . '...</span>';
                                } else {
                                    $content .= $run['run_notes'];
                                }
                                $content .= '</td>
                                <td>' . date(DATE_FORMAT, strtotime($run['run_createDtTi'])) . '</td>
                                <td>
                                    <button class="uk-button-secondary delete-run" title="Delete" data-id_run="' . $run['id_run'] . '"><i class="uk-icon-trash"></i></button>
                                </td>
                            </tr>';
                        }
                    } else {
                        $content .= '<tr><td colspan="11">No runs available</td></tr>';
                    }
                $content .= '</tbody>
            </table>
        </div>
	</div>';
}

echo $content;