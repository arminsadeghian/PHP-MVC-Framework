<?php

function view(string $path, array $data = []): void
{
    extract($data);
    $path = str_replace('.', '/', $path);
    $viewFullPath = __DIR__ . "/../views/$path.view.php";
    include_once $viewFullPath;
}

function siteUrl($route): string
{
    return $_ENV['APP_URL'] . $route;
}

function assetUrl($route): string
{
    return siteUrl("assets/" . $route);
}

function niceDump($var): void
{
    echo "<pre style='display: block; text-align: left; direction: ltr; background-color: #fff; border: 1px solid #b75520; border-left-width: 7px; border-radius: 5px; margin: 10px; padding: 10px 10px 0 10px !important; font-size: 17px !important;'>";
    var_dump($var);
    echo "</pre>";
}

function nice_dd($var): void
{
    niceDump($var);
    die();
}