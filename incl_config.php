<?

define('DB_HOST', "localhost");
define('DB_NAME', "vermintide");
define('DB_USER', "root");
define('DB_PASS', "1234");

$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

define("default_difficulty", "Cataclysm");