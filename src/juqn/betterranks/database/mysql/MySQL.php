<?php

declare(strict_types=1);

namespace juqn\betterranks\database\mysql;

use InvalidArgumentException;
use juqn\betterranks\BetterRanks;
use juqn\betterranks\database\mysql\query\async\AsyncQuery;
use mysqli;
use mysqli_result;
use mysqli_sql_exception;

final class MySQL {

    private static string $host, $username, $password, $database;
    private static int $port;

    public static function new(): mysqli {
        return new mysqli(self::$host, self::$username, self::$password, self::$database, self::$port);
    }

    public static function runAsync(AsyncQuery $asyncQuery): void {
        $asyncQuery->setHost([self::$host, self::$username, self::$password, self::$database, self::$port]);

        BetterRanks::getInstance()->getServer()->getAsyncPool()->submitTask($asyncQuery);
    }

    public static function run(string $query, ?\Closure $closure = null): void {
        try {
            $mysql = self::new();
            $result = $mysql->query($query);

            if (isset($closure)) {
                if (!$result instanceof mysqli_result) {
                    $closure();
                } else {
                    $rows = [];

                    while ($row = $result->fetch_assoc()) {
                        $rows[] = $row;
                    }
                    $closure($rows);
                }
            }
        } catch (mysqli_sql_exception $exception) {
            BetterRanks::getInstance()->getLogger()->error($exception->getMessage());
        }
    }

    public static function setCredentials(array $credentials): void {
        foreach ($credentials as $credential) {
            if (!isset($credential)) {
                throw new InvalidArgumentException('Missing mysql credentials.');
            }
        }
        self::$host = $credentials['hostname'];
        self::$port = (int) $credentials['port'];
        self::$username = $credentials['username'];
        self::$password = $credentials['password'];
        self::$database = $credentials['database'];
    }
}