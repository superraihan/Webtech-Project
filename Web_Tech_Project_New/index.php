<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';


$allowed_pages = ['home', 'login', 'register', 'about', 'contact', 'pets', 'user', 'admin', 'logout', 'forgot_pass'];

if (in_array($page, $allowed_pages)) {
    $controllerPath = "controllers/{$page}.php";
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
    } else {

        echo "404 Page Not Found - Controller Missing";
    }
} else {


    require_once 'controllers/home.php';
}
?>