<?php

declare(strict_types=1);

namespace juqn\betterranks\rank;

final class Rank {

    /**
     * @param string $name
     * @param string $format
     * @param string $nametagFormat
     * @param string $chatFormat
     * @param int $priority
     * @param string[] $permissions
     */
    public function __construct(
        private string $name,
        private string $format,
        private string $nametagFormat,
        private string $chatFormat,
        private int $priority,
        private array $permissions
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function getFormat(): string {
        return $this->format;
    }

    public function getNametagFormat(): string {
        return $this->nametagFormat;
    }

    public function getChatFormat(): string {
        return $this->chatFormat;
    }

    public function getPriority(): int {
        return $this->priority;
    }

    public function getPermissions(): array {
        return $this->permissions;
    }
}