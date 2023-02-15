<?php

declare(strict_types=1);

namespace juqn\betterranks\database\mysql;

final class Tables {

    public const CREATE_RANK_TABLE = "
create table if not exists ranks
(
    id          int  auto_increment
        primary key,
    xuid        varchar(50)    not null,
    name        varchar(50)    not null,
    rank_name   varchar(50)    not null,
    prefix_name varchar(50) default 'default'    not null,
    constraint xuid
        unique (xuid)
)";
}