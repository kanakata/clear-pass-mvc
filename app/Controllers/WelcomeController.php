<?php

namespace App\Controllers;

class WelcomeController
{
    public static function show()
    {
        return require ROOT . "/resources/views/welcome.php";
    }
}
