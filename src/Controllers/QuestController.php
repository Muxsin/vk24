<?php

declare(strict_types=1);

namespace Muhsin\VK\Controllers;

use Muhsin\VK\Models\Quest;
use Muhsin\VK\Models\User;

class QuestController
{
    private User $user_model;
    private Quest $quest_model;

    public function __construct(User $user_model, Quest $quest_model)
    {
        $this->user_model = $user_model;
        $this->quest_model = $quest_model;
    }

    public function create(): void
    {
        if (
            !isset($_POST['name']) ||
            !isset($_POST['cost']) ||
            empty(trim($_POST['name'])) ||
            empty(trim($_POST['cost']))
        ) {
            http_response_code(422);

            echo json_encode(['error' => 'Please fill all the required fields & None of the fields should be empty.']);

            return;
        }

        $result = $this->quest_model->create($_POST['name'], (int)$_POST['cost']);

        if (is_string($result)) {
            echo json_encode(['error' => $result]);

            return;
        }

        echo json_encode($result);
    }

    public function complete(): void
    {
        if (
            !isset($_POST['user_id']) ||
            empty(trim($_POST['user_id'])) ||
            !isset($_POST['quest_id']) ||
            empty(trim($_POST['quest_id']))
        ) {
            http_response_code(422);

            echo json_encode(['error' => 'Please fill all the required fields & None of the fields should be empty.']);

            return;
        }

        $user = $this->user_model->get((int)$_POST['user_id']);
        $quest = $this->quest_model->get((int)$_POST['quest_id']);

        if ($user === false || $quest === false) {
            echo json_encode(['error' => 'User|Quest not found.']);

            return;
        }

        $completion = $this->quest_model->findCompleted($user['id'], $quest['id']);

        if ($completion !== false) {
            echo json_encode(['error' => "User already completed this task."]);

            return;
        }

        $complete = $this->quest_model->complete($user['id'], $quest['id']);

        if ($complete === false) {
            echo json_encode(['error' => "Failed to complete the quest."]);

            return;
        }

        $balance = $user['balance'] + $quest['cost'];

        $completion = $this->user_model->updateBalance($user['id'], $balance);

        if (is_string($completion)) {
            echo json_encode(['error' => $completion]);

            return;
        }

        echo json_encode($completion);
    }
}
