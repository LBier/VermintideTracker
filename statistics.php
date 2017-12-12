<?php

// runs, sums and averages
$q = "SELECT count(id_run) as run_count, sum(run_duration) as duration_sum, avg(run_duration) as duration_avg,
sum(run_probability_red) as probability_red_sum, avg(run_probability_red) as probability_red_avg, (SELECT count(id_run) FROM vw_run WHERE rar_level = 5) as red_count FROM `vw_run`";
$result = select($q);
$general = $result[0];
//dump($general);
// group by map
$maps = select("SELECT map_name, count(id_run) as count FROM `vw_run` GROUP BY map_name ORDER BY count DESC, map_order ASC");
// group by difficulty
$difficulties = select("SELECT dif_name, count(id_run) as count FROM `vw_run` GROUP BY dif_name ORDER BY count DESC, dif_level DESC");
// group by item rarity
$rarities = select("SELECT rar_color, count(id_run) as count FROM `vw_run` GROUP BY rar_color ORDER BY count DESC, rar_level DESC");
// group by date
$days = select("SELECT DATE(`run_createDtTi`) as run_date, count(id_run) as count FROM `vw_run` GROUP BY run_date ORDER by run_date DESC");
$days = select("SELECT SUBSTRING(`run_createDtTi`, 1, 10) as run_date, count(id_run) as count FROM `vw_run` GROUP BY run_date ORDER by run_date DESC");
$months = select("SELECT SUBSTRING(`run_createDtTi`, 1, 7) as run_date, count(id_run) as count FROM `vw_run` GROUP BY run_date ORDER by run_date DESC");
// group by mods
$mods = select("SELECT m.mod_description, count(rm.id_run_mod) as count FROM `tbl_run_mod` as rm JOIN tbl_mod as m ON (rm.rm_mod_id = m.id_mod) GROUP BY m.id_mod ORDER BY count DESC");

$content = '<div class="uk-width-1-1">
    <div class="header">
        <h2 class="uk-float-left">Statistics</h2>
    </div>
</div>
<div class="uk-width-1-1">
    <div class="body">';
        if ($general['run_count'] == 0) {
            $content .= '<p>No runs, no stats!</p>';
        } else {
            $content .= '<div class="uk-grid">
                <div class="uk-width-1-1 uk-margin-bottom">
                    <p>You completed ' . $general['run_count'] . ' runs in ' . convertMinsToHoursMins($general['duration_sum']) . ' hours (&oslash; ' . round($general['duration_avg'], 2) . ' min).</p>
                    <p>The average chance to roll a red was ' . round($general['probability_red_avg'], 2) . '% (' . round($general['probability_red_sum'] / 100, 2) . ' reds over ' . $general['run_count'] . ' runs) and you got ' . $general['red_count'] . '.</p>
                </div>
                <div class="uk-width-1-3">
                    ' . render_table(array("Map", "# of runs"), $maps) . '
                </div>
                <div class="uk-width-2-3">
                    <div class="uk-grid">
                        <div class="uk-width-1-2">
                            ' . render_table(array("Difficulty", "# of runs"), $difficulties) . '
                        </div>
                        <div class="uk-width-1-2">
                            ' . render_table(array("Rarity", "# of Items"), $rarities) . '
                        </div>
                        <div class="uk-width-1-2">
                            ' . render_table(array("Month", "# of runs"), $months) . '
                            ' . render_table(array("Mod", "# of runs"), $mods) . '
                        </div>
                        <div class="uk-width-1-2">
                            ' . render_table(array("Day", "# of runs"), $days) . '
                        </div>
                    </div>
                </div>
            </div>';
        }
    $content .= '</div>
</div>';

echo $content;