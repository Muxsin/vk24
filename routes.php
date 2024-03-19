<?php

declare(strict_types=1);

use Muhsin\VK\Controllers\QuestController;
use Muhsin\VK\Controllers\UserController;
use Muhsin\VK\Core\Database;
use Muhsin\VK\Core\Route;
use Muhsin\VK\Models\Quest;
use Muhsin\VK\Models\User;

$database = Database::getInstance();
$user_model = new User($database);
$quest_model = new Quest($database);

$user_controller = new UserController($user_model, $quest_model);
$quest_controller = new QuestController($user_model, $quest_model);

return [
    Route::get('/api/users', [$user_controller, 'show']),
    Route::post('/api/users', [$user_controller, 'create']),
    Route::post('/api/quests', [$quest_controller, 'create']),
    Route::post('/api/quests/completed', [$quest_controller, 'complete']),
];
