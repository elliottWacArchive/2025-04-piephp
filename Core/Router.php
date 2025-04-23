<?php

namespace Core;

class Router
{
    private static $routes = [];

    public static function connect(string $url, array $route): void
    {
        self::$routes[$url] = $route;
    }

    public static function get(string $url): ?array
    {
        return self::$routes[$url] ?? null;
    }
    
    // Ajoutez cette méthode pour récupérer toutes les routes
    public static function getRoutes(): array
    {
        return self::$routes;
    }
}