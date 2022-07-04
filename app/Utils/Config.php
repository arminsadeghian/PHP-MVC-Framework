<?php

namespace App\Utils;

use App\Exceptions\ConfigFileNotFoundException;

class Config
{
    public static function getFileContents(string $fileName): array
    {
        $fullFilePath = realpath(__DIR__ . "/../../Configs/" . $fileName . ".php");

        if (!$fullFilePath) {
            throw new ConfigFileNotFoundException();
        }

        $fileContents = require $fullFilePath;
        return $fileContents;
    }

    public static function get(string $fileName, string $key = null): array
    {
        try {
            $fileContents = self::getFileContents($fileName);
            if (is_null($key)) {
                return $fileContents;
            }
        } catch (ConfigFileNotFoundException $e) {
            echo $e->getMessage();
        }

        return $fileContents[$key];
    }
}