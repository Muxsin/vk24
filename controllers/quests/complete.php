<?php

declare(strict_types=1);

use Muhsin\VK\Core\Database;
use Muhsin\VK\Models\Quest;
use Muhsin\VK\Models\User;

function completeQuest(int $user_id, int $quest_id): array|string
{
    $config = require 'config.php';
    $db = new Database($config['database'], 'root', 'secret');

    $quest_model = new Quest($db);
    $user_model = new User($db);

    $user = $user_model->get($user_id);
    $quest = $quest_model->get($quest_id);

    if ($user === false || $quest === false) {
        return "User|Quest not found.";
    }

    $completion = $quest_model->findCompleted($user_id, $quest_id);

    if ($completion !== false) {
        return "User already completed this task.";
    }

    $complete = $quest_model->complete($user_id, $quest_id);

    if ($complete === false) {
        return "Failed to complete the quest.";
    }

    $balance = $user['balance'] + $quest['cost'];

    return $user_model->updateBalance($user_id, $balance);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['user_id']) ||
        empty(trim($_POST['user_id'])) ||
        !isset($_POST['quest_id']) ||
        empty(trim($_POST['quest_id']))
    ) {
        http_response_code(422);

        echo json_encode(['error' => 'Please fill all the required fields & None of the fields should be empty.']);

        exit;
    }

    $completion = completeQuest((int)$_POST['user_id'], (int)$_POST['quest_id']);

    if (gettype($completion) === "string") {
        echo json_encode(['error' => $completion]);
    } else {
        echo json_encode($completion);
    }
}
