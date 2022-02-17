<?php
declare(strict_types=1);

use App\Application\Controllers\AuthController;
use App\Application\Controllers\UserController;
use Slim\App;

return function (App $app) {

    $app->get('/auth/signup', AuthController::class . ':get_auth_code');

    $app->post('/oauth/auth', AuthController::class . ':get_auth_token');

    $app->get('/user', UserController::class . ':get_current_user');

    $app->post('/registration', UserController::class . ':registration');
};
