<?php

// runs, sums and averages
$general = select("SELECT count(id_run), sum(run_duration), avg(run_duration), sum(run_probability_red), avg(run_probability_red) FROM `vw_run`");
// group by map
$maps = select("SELECT map_name, count(id_run) as count FROM `vw_run` GROUP BY map_name ORDER BY count DESC, id_map ASC");
// group by difficulty
$difficulties = select("SELECT dif_name, count(id_run) as count FROM `vw_run` GROUP BY dif_name ORDER BY count DESC, dif_level DESC");
// group by item rarity
$rarities = select("SELECT rar_color, count(id_run) as count FROM `vw_run` GROUP BY rar_color ORDER BY count DESC, rar_level DESC");
// group by date
$days = select("SELECT DATE(`run_createDtTi`) as run_date, count(id_run) as count FROM `vw_run` GROUP BY run_date ORDER by run_date DESC");
$days = select("SELECT SUBSTRING(`run_createDtTi`, 1, 10) as run_date, count(id_run) as count FROM `vw_run` GROUP BY run_date ORDER by run_date DESC");
$months = select("SELECT SUBSTRING(`run_createDtTi`, 1, 7) as run_date, count(id_run) as count FROM `vw_run` GROUP BY run_date ORDER by run_date DESC");
// group by mods
$mods = select("SELECT mod_name, count(id_mod) as count FROM `tbl_mod` as `mod`, tbl_run_mod as rm WHERE `mod`.id_mod = rm.rm_mod_id GROUP BY mod_name ORDER BY count DESC");

$content = '<div class="uk-width-1-1">
    <div class="header">
        <h2 class="uk-float-left">Statistics</h2>
    </div>
</div>
<div class="uk-width-1-1">
    <div class="body">
        <div class="uk-grid">
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
                    </div>
                    <div class="uk-width-1-2">
                        ' . render_table(array("Day", "# of runs"), $days) . '
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

echo $content;