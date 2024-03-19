<?php

declare(strict_types=1);

namespace Muhsin\VK\Controllers;

use Muhsin\VK\Models\Quest;
use Muhsin\VK\Models\User;

class UserController
{
    private User $user_model;
    private Quest $quest_model;

    public function __construct(User $user_model, Quest $quest_model)
    {
        $this->user_model = $user_model;
        $this->quest_model = $quest_model;
    }

    public function show(): void
    {
        if (
            !isset($_GET['user_id']) ||
            empty(trim($_GET['user_id']))
        ) {
            http_response_code(422);

            echo json_encode(['error' => 'Please fill all the required fields & None of the fields should be empty.']);

            return;
        }

        $user = $this->user_model->get((int)$_GET['user_id']);

        if ($user === false) {
            echo json_encode(['error' => 'User not found.']);

            return;
        }

        $quests = $this->quest_model->findAllCompleted($user['id']);

        echo json_encode([
            'user' => $user,
            'quests' => $quests,
        ]);
    }

    public function create(): void
    {
        if (
            !isset($_POST['name']) ||
            empty(trim($_POST['name']))
        ) {
            http_response_code(422);

            echo json_encode(['error' => 'Please fill all the required fields & None of the fields should be empty.']);

            exit;
        }

        $result = $this->user_model->create($_POST['name']);

        if (is_string($result)) {
            echo json_encode(['error' => $result]);

            return;
        }

        echo json_encode($result);
    }
}
