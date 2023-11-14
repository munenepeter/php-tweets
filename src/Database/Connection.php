<?php
namespace App\Database;

use PDO;

class Connection {

    private static $sqlite_database = __DIR__ . "/db.sqlite";
    //make a connection to the DB
    public static function make($config) {
        try {
            if ($config['connection'] === 'sqlite') {
                return new PDO("sqlite:" . self::$sqlite_database);
            }

            $dsn = $config['connection'] .':host=' . $config['host'] .';port=' . $config['port'].';dbname=' . $config['database'];

            return new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
        catch(\PDOException $e) {
            throw new \Exception($e->getMessage(), $e->getCode);
        }
    }
}

