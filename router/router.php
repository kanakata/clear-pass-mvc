<?php

namespace Router;

class Router
{
    //set your landing page
    private static ?string $landingPage = "/landing";
    private static array $allowedPages = [
        //set the pages that users are allowed to view.
        "/",
        "/403",
        "/404",
        "/farmer",
        "/hotel",
        "/index",
        "/landing",
        "/messages",
        "/orderDetail",
        "/orders",
        "/productForm",
        "/products",
        "/profile",
        "/register",
        "/show",
    ];

    public static function router(string $path)
    {
        $path = ($path === "/") ? self::$landingPage : $path;

        //checks if the the page requested is in the allowed array
        if (in_array($path, self::$allowedPages)) {

            return self::web($path);
        }
    }

    private static function web(string $path)
    {
        $controllerName = self::createControllerName($path);
        $fullClass = "App\Controllers\\" . $controllerName;

        if (class_exists($fullClass)) {

            $controller = new $fullClass();

            //inside your controllers create a show function or index.
            //in case you choose index then change the show() to index().
            return $controller->show();
        }
    }

    private static function createControllerName(string $path): string
    {

        $name = ucfirst(ltrim($path, '/'));
        $name = str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));

        return $name . "Controller";
    }
}
