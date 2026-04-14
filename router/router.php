<?php
namespace Router;
class Router{
    public static function Router(){
        if(isset($_SERVER['REQUEST_URI'])){
            session_start();
            $page =parse_url($_SERVER['REQUEST_URI'])['path'];
            $allowed_pages = [
                "/",
                "/department",
                "/register",
                "/login",
                "/pay_ship",
                "/pay_shipment",
                "/dashboard",
            ];
            if($page == "/"){
                return [
                    require_once ROOT . "/templates/register" . ".php"
                ];
            }else{
                if (in_array($page, $allowed_pages)) {
                    return [
                        require_once ROOT . "/templates" . $page . ".php"
                    ];
                }
            }
        }
    }
}