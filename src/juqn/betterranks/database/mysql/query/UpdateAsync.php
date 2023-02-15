<?php

declare(strict_types=1);

namespace juqn\betterranks\database\mysql\query;

use Closure;
use juqn\betterranks\database\mysql\query\async\QueryAsync;

final class UpdateAsync extends QueryAsync {

    public function __construct(
        string   $table,
        array    $data,
        array    $conditions,
        ?Closure $onComplete = null
    ) {
        $set = implode(', ', array_map(static fn($key, $value) => "{$key} = '$value'", array_keys($data), array_values($data)));
        $where = implode(' AND ', array_map(static fn($key, $value) => "$key = '{$value}'", array_keys($conditions), array_values($conditions)));
        parent::__construct("UPDATE {$table} SET {$set} WHERE {$where};", $onComplete);
    }
}