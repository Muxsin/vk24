<?php

function completeQuest($user_id, $quest_id) {
    global $conn;

    $user_stmt =  $conn->prepare("SELECT * FROM Users WHERE id = ?");
    $user_stmt->execute([$user_id]);

    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    $quest_stmt =  $conn->prepare("SELECT * FROM Quests WHERE id = ?");
    $quest_stmt->execute([$quest_id]);

    $quest = $quest_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !$quest) {
        return false;
    }

    $completion_stmt = $conn->prepare("SELECT * FROM UserQuests WHERE user_id = ? AND quest_id = ?");
    $completion_stmt->execute([$user_id, $quest_id]);

    $completion = $completion_stmt->fetch(PDO::FETCH_ASSOC);

    if ($completion) {
        return false;
    }

    $stmt = $conn->prepare("INSERT INTO UserQuests (user_id, quest_id, completed_at) VALUES (?, ?, NOW())");
    $stmt->execute([$user_id, $quest_id]);

    $balance = $user['balance'] + $quest['cost'];

    $stmt = $conn->prepare("UPDATE Users SET balance = ? WHERE id = ?");
    $stmt->execute([$balance, $user_id]);

    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['user_id']) ||
        empty(trim($_POST['user_id'])) ||
        !isset($_POST['quest_id']) ||
        empty(trim($_POST['quest_id']))
    ) {
        http_response_code(422);

        echo json_encode([
            'status' => 'error',
            'name' => 'Missing a mandatory argument',
            'message' => 'Please fill all the required fields & None of the fields should be empty.',
            'required_fields' => ['user', 'quest']
        ]);

        exit;
    }

    if (completeQuest($_POST['user_id'], $_POST['quest_id'])) {
        echo json_encode(['message' => 'Quest completed and reward awarded.']);
    } else {
        echo json_encode(['error' => 'Quest already completed or an error occurred.']);
    }
}
