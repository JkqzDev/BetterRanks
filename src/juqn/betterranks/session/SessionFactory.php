<?php

declare(strict_types=1);

namespace juqn\betterranks\session;

use juqn\betterranks\BetterRanks;
use juqn\betterranks\rank\RankFactory;
use pocketmine\player\Player;
use RuntimeException;

final class SessionFactory {

    /** @var Session[] */
    private static array $sessions = [];

    public static function getAll(): array {
        return self::$sessions;
    }

    public static function get(Player|string $player): ?Session {
        return self::$sessions[$player instanceof Player ? $player->getXuid() : $player] ?? null;
    }

    public static function create(Player $player): void {
        $rank = RankFactory::get(BetterRanks::getInstance()->getConfig()->get('rank-default', 'user'));

        if ($rank === null) {
            throw new RuntimeException('Default rank not exist.');
        }
        self::$sessions[$player->getXuid()] = $session = new Session($player->getXuid(), $player->getUniqueId()->getBytes(), $player->getName(), $rank);
        $session->updatePermissions($player);
    }
}