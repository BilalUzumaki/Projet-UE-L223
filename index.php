<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require 'model/Database.php';
require 'model/UserModel.php';
require 'model/RecipeModel.php';
require 'controller/UserController.php';
require 'controller/RecipeController.php';

$page = $_GET['page'] ?? 'home';

switch($page) {
    case 'login':
        $controller = new UserController();
        $controller->login();
        break;
    case 'register':
        $controller = new UserController();
        $controller->register();
        break;
    case 'logout':
        $controller = new UserController();
        $controller->logout();
        break;
    case 'add_recipe':
        $controller = new RecipeController();
        $controller->add();
        break;
    case 'recipe':
        $controller = new RecipeController();
        $controller->view();
        break;
    case 'recipes':
        $controller = new RecipeController();
        $controller->list();
        break;
    default:
        require_once 'model/RecipeModel.php';
        $recipes = Recipe::latest(5);
        include 'view/home.php';
}