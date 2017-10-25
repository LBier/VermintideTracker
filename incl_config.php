<?php

define('DB_HOST', "localhost");
define('DB_NAME', "vermintide");
define('DB_USER', "root");
define('DB_PASS', "1234");

$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

define("DEFAULT_DIFFICULTY", "Cataclysm");
define("DEFAULT_RARITY", "Exotic");
define("DEFAULT_ORDER", "run_createDtTi");
define("DEFAULT_DIRECTION", "desc");

define("DATE_FORMAT", "d.m.Y H:i");