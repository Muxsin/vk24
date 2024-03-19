<?php

use Muhsin\VK\Controllers\QuestController;
use Muhsin\VK\Controllers\UserController;
use Muhsin\VK\Core\Database;
use Muhsin\VK\Models\Quest;
use Muhsin\VK\Models\User;

$config = require 'config.php';
$db = new Database($config['database'], 'root', 'secret');

$user_model = new User($db);
$quest_model = new Quest($db);

$user_controller = new UserController($user_model, $quest_model);
$quest_controller = new QuestController($user_model, $quest_model);

$routes = [
    [
        'route' => '/api/users',
        'method' => 'GET',
        'controller' => [$user_controller, 'show'],
    ],
    [
        'route' => '/api/users',
        'method' => 'POST',
        'controller' => [$user_controller, 'create'],
    ],
    [
        'route' => '/api/quests',
        'method' => 'POST',
        'controller' => [$quest_controller, 'create'],
    ],
    [
        'route' => '/api/quests/completed',
        'method' => 'POST',
        'controller' => [$quest_controller, 'complete'],
    ]
];

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'];
$route = null;

foreach ($routes as $datum) {
    if ($datum['route'] == $uri && $datum['method'] == $method) {
        $route = $datum;

        break;
    }
}

if ($route) {
    call_user_func($route['controller']);
} else {
    http_response_code(404);
    header('Content-Type: application/json');

    echo json_encode(['error' => 'The requested resource was not found.']);

    exit;
}
