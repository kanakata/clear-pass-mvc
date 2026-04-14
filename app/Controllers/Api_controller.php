<?php

namespace App\Controllers;

use App\Models\Auth\Auth;

class Api_controller extends Auth
{
    public static function Login()
    {
        if (isset($_POST['login']) && !empty($_POST['password'])) {
            return parent::Log_in();
        }
    }
    public static function No_debt()
    {
        if (isset($_GET["action"]) && $_GET["action"] == "no_debt") {
            return parent::No_debt();
        }
    }
    public static function Pay_physically()
    {
        return parent::Pay_physically();
    }
}
