<?php

define('DB_HOST', "localhost");
define('DB_NAME', "vermintide");
define('DB_USER', "root");
define('DB_PASS', "");

$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

/* Easy, Normal, Hard, Nightmare, Cataclysm */
define("DEFAULT_DIFFICULTY", "Cataclysm");
/* Plentiful (White), Common (Green), Rare (Blue), Exotic (Orange), Veteran (Red) */
define("DEFAULT_RARITY", "Exotic");
/* Victor Saltspyre, Kerillian, Bardin Goreksson, Sienna Fuegonasus, Markus Kruber */
define("DEFAULT_HERO", "");

/* possible order fields
dif_level
map_name
run_duration
pro_dice_string
run_probability_red
rar_level
run_createDtTi
*/
define("DEFAULT_ORDER", "run_createDtTi");
/* desc or asc */
define("DEFAULT_DIRECTION", "desc");

define("DATE_FORMAT", "d.m.Y H:i");

$navigation = array("tracker" => "Overview", "statistics" => "Statistics");