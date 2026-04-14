<?php
define("ROOT", dirname(__DIR__));
require_once ROOT . "/vendor/autoload.php";
use Router\Router;
$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->load();
Router::Router();