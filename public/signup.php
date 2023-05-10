<?php

require_once ('../config/connection.php');

$request = json_decode(array_key_first($_REQUEST), true);
//username must unique
if (get("username = '{$request['username']}'") != false) {
    $request['password'] = password_hash($request['password'], PASSWORD_DEFAULT);
    $request['role'] = 0;
    save($request);
}
?>