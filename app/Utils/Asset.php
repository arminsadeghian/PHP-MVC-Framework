<?php

namespace App\Utils;

class Asset
{
    public static function get(string $route): string
    {
        return $_ENV['APP_URL'] . 'assets/' . $route;
    }

    public static function css(string $route): string
    {
        return $_ENV['APP_URL'] . 'assets/css/' . $route;
    }

    public static function js(string $route): string
    {
        return $_ENV['APP_URL'] . 'assets/js/' . $route;
    }

    public static function image(string $route): string
    {
        return $_ENV['APP_URL'] . 'assets/image/' . $route;
    }
}
