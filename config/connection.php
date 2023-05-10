<?php

function connection()
{
    // Create connection
    $conn = new mysqli('localhost', 'nghia', 'pass123', 'jwt');

    // Check connection
    if ($conn->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function get($condition = null, mixed $cols = '*', $table = 'user')
{
    if ($cols != '*') {
        $cols = implode(', ', $cols);
    }
    $query = "SELECT {$cols} FROM {$table}";
    if ($condition) {
        $query .= " WHERE {$condition}";
    }
    $result = connection()->query($query);

    return $result->fetch_all(MYSQLI_ASSOC);
}

function save($data, $table = 'user', mixed $cols = '*')
{
    if ($cols != '*') {
        $cols = implode(', ', $cols);
    }
    $query = "INSERT INTO {$table} (`name`, `username`, `password`, `role`) VALUES ('{$data["name"]}', '{$data["username"]}', '{$data["password"]}', '{$data["role"]}')";
    try {
        connection()->query($query);
        return true;
    } catch (\PDOException $e) {
        return $e->getMessage();
    }
}

function delete($id = null, $table = 'user', mixed $cols = '*')
{
    $cols = implode(', ', $cols);
    $query = "DELETE FROM {$table}" . ($id) ? "WHERE id = {$id}" : "";
    try {
        connection()->query($query);
        return 'Delete success';
    } catch (\PDOException $e) {
        return $e->getMessage();
    }
}
?>