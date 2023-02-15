<?php

declare(strict_types=1);

namespace juqn\betterranks\prefix;

use juqn\betterranks\BetterRanks;
use pocketmine\utils\Config;

final class PrefixFactory {

    /** @var Prefix[] */
    private static array $prefixes = [];

    public static function getAll(): array {
        return self::$prefixes;
    }

    public static function get(string $name): ?Prefix {
        return self::$prefixes[$name] ?? null;
    }

    public static function loadAll(): void {
        $config = new Config(BetterRanks::getInstance()->getDataFolder() . 'prefixes.yml', Config::YAML);
        $data = $config->getAll();

        foreach ($data as $name => $prefix) {
            self::$prefixes[$name] = new Prefix($name, $prefix['format'], $prefix['permissions'] ?? []);
        }
        BetterRanks::getInstance()->getLogger()->info('Prefixes loaded ' . count(self::$prefixes));
    }
}