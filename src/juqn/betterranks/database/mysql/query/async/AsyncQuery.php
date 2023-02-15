<?php

declare(strict_types=1);

namespace juqn\betterranks\database\mysql\query\async;

use juqn\betterranks\database\mysql\MySQL;
use mysqli;
use pocketmine\scheduler\AsyncTask;

abstract class AsyncQuery extends AsyncTask {

    private bool $failed = false;

    private string $host, $username, $password, $database;
    private int $port;

    abstract public function query(mysqli $mysqli): void;

    public function setHost(array $credentials): void {
        $this->host = $credentials[0];
        $this->username = $credentials[1];
        $this->password = $credentials[2];
        $this->database = $credentials[3];
        $this->port = $credentials[4];
    }

    public function isFailed(): bool {
        return $this->failed;
    }

    public function onRun(): void {
        try {
            $mysql = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
            $this->query($mysql);
            $mysql->close();
        } catch (\mysqli_sql_exception $exception) {
            $this->failed = true;
        }
    }
}