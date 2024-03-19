<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['name']) ||
        empty(trim($_POST['name']))
    ) {
        http_response_code(422);

        echo json_encode(['error' => 'Please fill all the required fields & None of the fields should be empty.']);

        exit;
    }

    $user_exist_stmt = $conn->prepare("SELECT * FROM Users WHERE name = ?");
    $user_exist_stmt->execute([$_POST['name']]);

    $user = $user_exist_stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        http_response_code(422);

        echo json_encode(['error' => 'User with that name already exists.']);

        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO users (name) VALUES (?)");
        $stmt->execute([$_POST['name']]);

        $user_stmt = $conn->prepare("SELECT * FROM Users WHERE id = ?");
        $user_stmt->execute([$conn->lastInsertId()]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($user);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to create user.']);
    }
}
