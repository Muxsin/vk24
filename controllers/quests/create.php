<?php

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

    $quest_exist_stmt = $conn->prepare("SELECT * FROM Quests WHERE name = ?");
    $quest_exist_stmt->execute([$_POST['name']]);

    $quest = $quest_exist_stmt->fetch(PDO::FETCH_ASSOC);

    if ($quest) {
        http_response_code(422);

        echo json_encode(['error' => 'Quest with that name already exists.']);

        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO quests (name, cost) VALUES (?, ?)");
        $stmt->execute([$_POST['name'], $_POST['cost']]);

        $quest_stmt = $conn->prepare("SELECT * FROM Quests WHERE id = ?");
        $quest_stmt->execute([$conn->lastInsertId()]);
        $quest = $quest_stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($quest);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to create quest.']);
    }
}
