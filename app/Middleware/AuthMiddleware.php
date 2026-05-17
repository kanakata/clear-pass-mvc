<?php
namespace App\Middleware;
class AuthMiddleware {
    public function handle():void{
        if(empty($_SESSION['user'])){ header('Location: '.BASE_URL.'/auth/login'); exit; }
    }
}
