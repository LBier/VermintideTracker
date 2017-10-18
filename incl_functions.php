<?php

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

function get_sort_buttons($new_sort) {
    global $sort, $order;

    $content = '<a class="' . ($sort == $new_sort && $order == 'desc' ? 'active-link' : 'inactive-link') . '" title="Sort Down" href="index.php?sort=' . $new_sort . '&order=desc">
        <i class="uk-icon-chevron-down"></i>
    </a>
    <a class="' . ($sort == $new_sort && $order == 'asc' ? 'active-link' : 'inactive-link') . '" title="Sort Up" href="index.php?sort=' . $new_sort . '&order=asc">
        <i class="uk-icon-chevron-up"></i>
    </a>';

    return $content;
}