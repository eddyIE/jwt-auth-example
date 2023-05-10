<?php

require_once ('connection.php');

class User {

    private $conn;

    function select($table, mixed $cols = '*', $condition = null) {
        $conn = $GLOBALS['conn'];
        $cols = implode(', ', $cols);
        $query = "SELECT {$cols} FROM {$table}" . ($condition) ? " WHERE {$condition}" : "";
        $result = $conn->query($query);

        return $result->fetch_assoc();
    }

    function insert($table, mixed $cols = '*', $condition = null) {
        $conn = $GLOBALS['conn'];
        $cols = implode(', ', $cols);
        $query = "SELECT {$cols} FROM {$table}" . ($condition) ? " WHERE {$condition}" : "";
        $result = $conn->query($query);

        return $result->fetch_assoc();
    }
}

?>
