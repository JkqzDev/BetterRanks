<?php

declare(strict_types=1);

namespace juqn\betterranks\session;

use juqn\betterranks\prefix\Prefix;
use juqn\betterranks\rank\Rank;
use pocketmine\player\Player;

final class Session {

    public function __construct(
        private string $xuid,
        private string $name,
        private Rank $rank,
        private ?Prefix $prefix = null
    ) {}

    public function getXuid(): string {
        return $this->xuid;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getChatFormat(Player $player, string $message): string {
        $rank = $this->rank;
        $prefix = $this->prefix;

        return str_replace(['{prefix}', '{player}', '{message}'], [$prefix?->getFormat() ?? '', $player->getName(), $message], $rank->getChatFormat());
    }

    public function getNametagFormat(Player $player): string {
        $rank = $this->rank;
        $prefix = $this->prefix;

        return str_replace(['{prefix}', '{player}'], [$prefix?->getFormat() ?? '', $player->getName()], $rank->getNametagFormat());
    }

    public function getRank(): Rank {
        return $this->rank;
    }

    public function getPrefix(): ?Prefix {
        return $this->prefix;
    }

    public function setRank(Rank $rank): void {
        $this->rank = $rank;
    }

    public function setPrefix(?Prefix $prefix): void {
        $this->prefix = $prefix;
    }
}