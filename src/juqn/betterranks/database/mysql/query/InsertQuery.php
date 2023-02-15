<?php

declare(strict_types=1);

namespace juqn\betterranks\database\mysql\query;

use Closure;
use juqn\betterranks\database\mysql\query\async\QueryAsync;

final class InsertQuery extends QueryAsync {

    public function __construct(
        string   $table,
        array    $data,
        ?Closure $onComplete = null
    ) {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(static fn($value) => "'$value'", array_values($data)));

        parent::__construct("INSERT INTO {$table} ({$columns}) VALUES ({$values});", $onComplete);
    }
}