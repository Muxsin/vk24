<?php

$routes = [
    [
        'route' => '/api/users',
        'method' => 'GET',
        'path' => 'controllers/user.php',
    ],
    [
        'route' => '/api/users',
        'method' => 'POST',
        'path' => 'controllers/user.php',
    ],
    [
        'route' => '/api/quests',
        'method' => 'POST',
        'path' => 'controllers/quest.php',
    ],
    [
        'route' => '/api/quests/completed',
        'method' => 'POST',
        'path' => 'controllers/completeQuest.php',
    ]
];

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];
$route = null;

foreach($routes as $datum) {
    if ($datum['route'] == $uri && $datum['method'] == $method) {
        $route = $datum;

        break;
    }
}

if ($route) {
    require $route['path'];
} else {
    http_response_code(404);
    header('Content-Type: application/json');

    echo json_encode(['error' => 'The requested resource was not found.']);

    exit;
}
