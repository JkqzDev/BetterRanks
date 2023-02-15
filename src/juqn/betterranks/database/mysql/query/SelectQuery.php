<?php

declare(strict_types=1);

namespace juqn\betterranks\database\mysql\query;

use Closure;
use juqn\betterranks\database\mysql\query\async\QueryAsync;

final class SelectQuery extends QueryAsync {

    public function __construct(
        string   $table,
        array    $conditions,
        string   $_extra = '',
        ?Closure $onComplete = null,
        string   $columns = '*'
    ) {
        if ($conditions === []) {
            parent::__construct(sprintf('SELECT %s FROM %s %s;', $columns, $table, $_extra));
            return;
        }
        $where = implode(' AND ', array_map(static fn($key, $value) => "$key = '$value'", array_keys($conditions), array_values($conditions)));
        parent::__construct(sprintf('SELECT %s FROM %s WHERE %s %s;', $columns, $table, $where, $_extra), $onComplete);
    }
}