<?php

declare(strict_types=1);

namespace juqn\betterranks\prefix;

final class Prefix {

    public function __construct(
        private string $name,
        private string $format,
        private array $permissions
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function getFormat(): string {
        return $this->format;
    }

    public function getPermissions(): array {
        return $this->permissions;
    }
}