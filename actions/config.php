<?php
function getDbConnection() {
    $host = "192.168.1.88";
    $port=3306;
    $username = "root";
    $password = "ashish";
    $dbname = "sarathi";
    $conn = new mysqli( hostname: $host, username: $username, password: $password, database: $dbname, port:$port);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

