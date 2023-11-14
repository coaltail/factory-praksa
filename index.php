<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once 'routes.php';
use App\Connection\Connection;
use Dotenv\Dotenv;
use \App\Router\Router;
use \App\Request\Request;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$req = new Request();
$conn = Connection::getInstance();
$userData = [
    'username' => 'john_doe',
    'email' => 'john.doe@example.com',
    'password' => 'hashed_password',
    'created_at' => '2023-11-14 12:00:00'
];


echo $conn->insert('users', $userData);
$response = Router::resolve($req);

echo $response->send();