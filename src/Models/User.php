<?php

declare(strict_types=1);

namespace Muhsin\VK\Models;

use Muhsin\VK\Core\Database;

class User
{
    private Database $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get(int $id): false|array
    {
        return $this->db->query('SELECT * FROM Users WHERE id = :id', ['id' => $id])->findOrFail();
    }

    public function create(string $name): string|array
    {
        $user = $this->db->query('SELECT * FROM Users WHERE name = :name', ['name' => $name])->find();

        if ($user) {
            return "User with this name already exists.";
        }

        $id = $this->db->query('INSERT INTO users (name) VALUES (:name)', ['name' => $name])->insert();

        if ($id === false) {
            return "Failed to create user.";
        }

        return $this->get($id);
    }

    public function updateBalance(int $id, int $balance): false|array|string
    {
        $user = $this->get($id);

        if ($user === false) {
            return "User not found.";
        }

        if ($this->db->query(
                'UPDATE Users SET balance = :balance WHERE id = :id',
                ['balance' => $balance, 'id' => $id]
            )->execute() === false) {
            return "Failed to update user.";
        }

        return $this->get($id);
    }
}
