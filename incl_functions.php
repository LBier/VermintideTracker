<?

function dump($var) {
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

function get_request($key) {
	if (isset($_REQUEST[$key])) {
		return $_REQUEST[$key];
	}
	return null;
}

function select($query) {
    global $pdo;

    $select = $pdo->prepare($query);
    $select->execute();

    return $select->fetchAll(PDO::FETCH_ASSOC);
}