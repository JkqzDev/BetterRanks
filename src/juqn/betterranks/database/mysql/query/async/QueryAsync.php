<?php

declare(strict_types=1);

namespace juqn\betterranks\database\mysql\query\async;

use juqn\betterranks\BetterRanks;
use mysqli;

class QueryAsync extends AsyncQuery {

    private ?string $rows = null;

    public function __construct(
        private $splQuery,
        private ?\Closure $onComplete = null
    ) {}

    public function query(mysqli $mysqli): void {
        $result = $mysqli->query($this->splQuery);

        if ($result instanceof \mysqli_result) {
            $rows = [];

            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->rows = serialize($rows);
        }
    }

    public function onCompletion(): void {
        if ($this->isFailed()) {
            BetterRanks::getInstance()->getLogger()->error('Failed to execute query: ' . $this->splQuery);
            return;
        }

        if (!isset($this->onComplete)) {
            return;
        }

        if (isset($this->rows)) {
            $this->onComplete?->__invoke(unserialize($this->rows, ['allowed_classes' => false]));
            return;
        }
        $this->onComplete?->__invoke([]);
    }
}