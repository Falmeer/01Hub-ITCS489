<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoload core files
require_once '../app/core/Controller.php';
require_once '../app/core/Model.php';
require_once '../app/core/Database.php';

// Parse URL (e.g. index.php?url=auth/login)
$url = $_GET['url'] ?? 'pages/home';
$url = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));

// Get controller and method
$controllerName = ucfirst($url[0]) . 'Controller';
$method = $url[1] ?? 'index';
$params = array_slice($url, 2);

// Load and dispatch controller
$controllerPath = "../app/controllers/$controllerName.php";

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName;

    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
    } else {
        http_response_code(404);
        echo "❌ Method '<strong>$method</strong>' not found in controller <strong>$controllerName</strong>.";
    }
} else {
    http_response_code(404);
    echo "❌ Controller <strong>'$controllerName'</strong> not found.";
}
