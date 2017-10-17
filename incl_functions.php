<?

function dump($var) {
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

function get_request($key, $default = null) {
	if (isset($_REQUEST[$key])) {
		return $_REQUEST[$key];
	}
	return $default;
}

function select($query) {
    global $pdo;

    $select = $pdo->prepare($query);
    $select->execute();

    return $select->fetchAll(PDO::FETCH_ASSOC);
}

function get_parameter($query, $parameter) {
    $result = select($query);

    if (!empty($result)) {
        return $result[0][$parameter];
    }
    return null;
}