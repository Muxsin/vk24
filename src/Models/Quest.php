<?php

declare(strict_types=1);

namespace Muhsin\VK\Models;

use Muhsin\VK\Core\Database;

class Quest
{
    private Database $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get(int $id): false|array
    {
        return $this->db->query('SELECT * FROM Quests WHERE id = :id', ['id' => $id])->findOrFail();
    }

    public function create(string $name, int $cost): string|array
    {
        $user = $this->db->query('SELECT * FROM Quests WHERE name = :name', ['name' => $name])->find();

        if ($user) {
            return "Quest with this name already exists.";
        }

        $id = $this->db->query(
            'INSERT INTO Quests (name, cost) VALUES (:name, :cost)',
            ['name' => $name, 'cost' => $cost]
        )->insert();

        if ($id === false) {
            return "Failed to create quest.";
        }

        return $this->get($id);
    }

    public function findAllCompleted(int $user_id): array
    {
        return $this->db->query(
            'SELECT q.*, uq.completed_at as completed_at FROM UserQuests uq LEFT JOIN Quests q ON uq.quest_id = q.id WHERE uq.user_id = :user_id',
            ['user_id' => $user_id]
        )->findAll();
    }

    public function findCompleted(int $user_id, int $quest_id): false|array
    {
        return $this->db->query(
            'SELECT * FROM UserQuests WHERE user_id = :user_id AND quest_id = :quest_id',
            ['user_id' => $user_id, 'quest_id' => $quest_id]
        )->find();
    }

    public function complete(int $user_id, int $quest_id): false|int
    {
        return $this->db->query(
            'INSERT INTO UserQuests (user_id, quest_id, completed_at) VALUES (:user_id, :quest_id, NOW())',
            ['user_id' => $user_id, 'quest_id' => $quest_id]
        )->insert();
    }
}
