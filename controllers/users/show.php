<?php

declare(strict_types=1);

use Muhsin\VK\Core\Database;
use Muhsin\VK\Models\Quest;
use Muhsin\VK\Models\User;

if (
    !isset($_GET['user_id']) ||
    empty(trim($_GET['user_id']))
) {
    http_response_code(422);

    echo json_encode(['error' => 'Please fill all the required fields & None of the fields should be empty.']);

    exit;
}

$config = require 'config.php';
$db = new Database($config['database'], 'root', 'secret');

$user = (new User($db))->get((int)$_GET['user_id']);

if ($user === false) {
    echo json_encode(['error' => 'User not found.']);

    exit;
}

$quests = (new Quest($db))->findAllCompleted($user['id']);

echo json_encode([
    'user' => $user,
    'quests' => $quests,
]);
