<?php

class Database
{
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {

            $databaseUrl = getenv('DATABASE_URL');

            if (!$databaseUrl) {
                die("DATABASE_URL not found");
            }

            $db = parse_url($databaseUrl);

            $host = $db['host'];
            $port = $db['port'];
            $user = $db['user'];
            $pass = $db['pass'];
            $dbname = ltrim($db['path'], '/');

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

            self::$pdo = new PDO(
                $dsn,
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }

        return self::$pdo;
    }
}