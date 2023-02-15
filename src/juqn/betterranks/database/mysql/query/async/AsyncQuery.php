<?php

declare(strict_types=1);

namespace juqn\betterranks\database\mysql\query\async;

use juqn\betterranks\database\mysql\MySQL;
use mysqli;
use pocketmine\scheduler\AsyncTask;

abstract class AsyncQuery extends AsyncTask {

    private bool $failed = false;

    abstract public function query(mysqli $mysqli): void;

    public function isFailed(): bool {
        return $this->failed;
    }

    public function onRun(): void {
        try {
            $mysql = MySQL::new();
            $this->query($mysql);
            $mysql->close();
        } catch (\mysqli_sql_exception $exception) {
            $this->failed = true;
        }
    }
}