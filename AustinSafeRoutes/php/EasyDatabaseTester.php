<?php
/**
 * Copyright (c) 2018. Challstrom. All Rights Reserved.
 */

/**
 * Created by IntelliJ IDEA.
 * User: tchallst
 * Date: 27-Mar-18
 * Time: 08:10 PM
 */

if (isset($_GET['key'])) {
    if ($_GET['key'] == '7abh92') {
        require_once __DIR__ . '/EasyDatabase.php';
        $out = [];
        $out[] = EasyDatabase::query("INSERT INTO mapdir (mapdir_data, route_name) VALUES('$data', '$routeName')");;
        echo json_encode($out);
    } else {
        http_response_code(403);
    }
} else {
    http_response_code(403);
}