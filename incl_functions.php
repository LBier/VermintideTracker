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

function get_sort_buttons($new_order) {
    global $order, $direction;

    $content = '<a class="' . ($order == $new_order && $direction == 'desc' ? 'active-link' : 'inactive-link') . '" title="Sort Down" href="index.php?order=' . $new_order . '&direction=desc">
        <i class="uk-icon-chevron-down"></i>
    </a>
    <a class="' . ($order == $new_order && $direction == 'asc' ? 'active-link' : 'inactive-link') . '" title="Sort Up" href="index.php?order=' . $new_order . '&direction=asc">
        <i class="uk-icon-chevron-up"></i>
    </a>';

    return $content;
}

function render_table($head, $rows) {
    $table = '<table class="uk-table uk-table-striped">
        <thead>
            <tr>';
            foreach ($head as $value) {
                $table .= '<th>' . $value . '</th>';
            }
            $table .= '</tr>
        </thead>
        <tbody>';
        foreach ($rows as $row) {
            $table .= '<tr>';
            foreach ($row as $value) {
                $table .= '<td>' . $value . '</td>';
            }
            $table .= '</tr>';
        }
        $table .= '</tbody>
    </table>';

    return $table;
}