<?php

declare(strict_types=1);

namespace Muhsin\VK\Core;

use PDO;

class Database
{
    public PDO $connection;

    public function __construct(array $config, string $username = 'root', string $password = '')
    {
        $dsn = 'mysql:' . http_build_query($config, '', ';');

        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function query(string $query, array $params = []): Query
    {
        $statement = $this->connection->prepare($query);

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }

        return new Query($this->connection, $statement);
    }
}
