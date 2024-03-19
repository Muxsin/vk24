<?php

declare(strict_types=1);

use Muhsin\VK\Core\Database;
use Muhsin\VK\Models\Quest;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['name']) ||
        !isset($_POST['cost']) ||
        empty(trim($_POST['name'])) ||
        empty(trim($_POST['cost']))
    ) {
        http_response_code(422);

        echo json_encode(['error' => 'Please fill all the required fields & None of the fields should be empty.']);

        exit;
    }

    $config = require 'config.php';
    $db = new Database($config['database'], 'root', 'secret');

    $result = (new Quest($db))->create($_POST['name'], (int)$_POST['cost']);

    if (gettype($result) === 'string') {
        echo json_encode(['error' => $result]);

        exit;
    }

    echo json_encode($result);
}
