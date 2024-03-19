<?php

if (
    !isset($_GET['user_id']) ||
    empty(trim($_GET['user_id']))
) {
    http_response_code(422);

    echo json_encode(['error' => 'Please fill all the required fields & None of the fields should be empty.']);

    exit;
}

$user_exist_stmt = $conn->prepare("SELECT * FROM Users WHERE id = ?");
$user_exist_stmt->execute([$_GET['user_id']]);

$user = $user_exist_stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);

    echo json_encode(['error' => 'User not found.']);

    exit;
}

$user_quests_stmt = $conn->prepare("SELECT q.*, uq.completed_at as completed_at FROM UserQuests uq LEFT JOIN Quests q ON uq.quest_id = q.id WHERE uq.user_id = ?");
$user_quests_stmt->execute([$_GET['user_id']]);

$quests = $user_quests_stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'user' => $user,
    'quests' => $quests,
]);
