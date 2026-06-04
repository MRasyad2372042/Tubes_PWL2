<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db = 'tubes_pwl2';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo "CONNECT_ERROR: " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

$res = $mysqli->query("SHOW COLUMNS FROM bhp_items");
if (! $res) {
    echo "QUERY_ERROR: " . $mysqli->error . PHP_EOL;
    exit(1);
}

$cols = [];
while ($row = $res->fetch_assoc()) {
    $cols[] = $row;
}

echo json_encode($cols, JSON_PRETTY_PRINT) . PHP_EOL;

$mysqli->close();
