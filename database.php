<?php

try {
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
} catch (PDOException $exception) {
    http_response_code(500);
    header('Content-Type: application/json');

    echo json_encode(['error' => 'Internal server error']);
}
