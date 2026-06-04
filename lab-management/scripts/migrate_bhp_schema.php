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

function hasColumn($mysqli, $table, $col) {
    $res = $mysqli->query("SHOW COLUMNS FROM {$table} LIKE '{$col}'");
    return $res && $res->num_rows > 0;
}

$changes = [];
if (!hasColumn($mysqli, 'bhp_items', 'name')) {
    $q = "ALTER TABLE bhp_items ADD COLUMN name VARCHAR(255) NULL";
    if ($mysqli->query($q)) {
        $changes[] = "Added column 'name'";
        // populate from item_name if available
        $mysqli->query("UPDATE bhp_items SET name = item_name WHERE name IS NULL AND item_name IS NOT NULL");
    } else {
        echo "ERROR adding name: " . $mysqli->error . PHP_EOL;
    }
}

if (!hasColumn($mysqli, 'bhp_items', 'min_stock')) {
    $q = "ALTER TABLE bhp_items ADD COLUMN min_stock INT NOT NULL DEFAULT 5";
    if ($mysqli->query($q)) {
        $changes[] = "Added column 'min_stock' with default 5";
    } else {
        echo "ERROR adding min_stock: " . $mysqli->error . PHP_EOL;
    }
}

if (empty($changes)) {
    echo "No schema changes needed." . PHP_EOL;
} else {
    echo json_encode($changes, JSON_PRETTY_PRINT) . PHP_EOL;
}

$mysqli->close();
