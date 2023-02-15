<?php

declare(strict_types=1);

namespace juqn\betterranks\session;

use juqn\betterranks\BetterRanks;
use juqn\betterranks\database\mysql\MySQL;
use juqn\betterranks\database\mysql\query\InsertQuery;
use juqn\betterranks\database\mysql\query\SelectQuery;
use juqn\betterranks\database\mysql\query\UpdateAsync;
use juqn\betterranks\prefix\Prefix;
use juqn\betterranks\prefix\PrefixFactory;
use juqn\betterranks\rank\Rank;
use juqn\betterranks\rank\RankFactory;
use pocketmine\player\Player;

final class Session {

    public function __construct(
        private string $xuid,
        private string $uuid,
        private string $name,
        private Rank $rank,
        private ?Prefix $prefix = null
    ) {
        MySQL::runAsync(new SelectQuery('ranks', ['xuid' => $xuid], '', function (array $rows): void {
                if (count($rows) === 0) {
                    MySQL::runAsync(new InsertQuery('ranks', [
                            'xuid' => $this->xuid,
                            'name' => $this->name,
                            'rank_name' => BetterRanks::getInstance()->getConfig()->get('rank-default', 'user')
                        ]
                    ));
                } else {
                    $data = $rows[0];
                    $rankName = $data['rank_name'];
                    $prefixName = $data['prefix_name'];

                    $rank = RankFactory::get($rankName);
                    $prefix = $prefixName === 'default' ? null : PrefixFactory::get($prefixName);

                    if ($rank === null) {
                        BetterRanks::getInstance()->getLogger()->notice('[Session #' . $this->xuid . '] Rank ' . $rankName . ' no exists.');
                        $rank = RankFactory::get(BetterRanks::getInstance()->getConfig()->get('rank-default', 'user'));
                    }
                    $this->rank = $rank;
                    $this->prefix = $prefix;
                }
            }
        ));
    }

    public function getXuid(): string {
        return $this->xuid;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getChatFormat(Player $player, string $message): string {
        $rank = $this->rank;
        $prefix = $this->prefix;
        $factionName = '';

        if (BetterRanks::getInstance()->getConfig()->get('plugin-extension.enable', false)) {
            $session = \kitmap\session\SessionFactory::get($player);
            $faction = $session?->getFaction();

            if ($faction !== null) {
                $factionName = $faction->getName() . ' ';
            }
        }
        return str_replace(['{faction}', '{prefix}', '{rank}', '{player}', '{message}'], [$factionName, $prefix?->getFormat() . ' ' ?? '', $rank->getFormat(), $player->getName(), $message], $rank->getChatFormat());
    }

    public function getNametagFormat(Player $player): string {
        $rank = $this->rank;
        $prefix = $this->prefix;
        $faction = '';

        if (BetterRanks::getInstance()->getConfig()->get('plugin-extension.enable', false)) {
            $session = \kitmap\session\SessionFactory::get($player);
            $faction = $session?->getFaction() === null ? '' : $session->getFaction()->getName();
        }
        return str_replace(['{faction}', '{prefix}', '{rank}', '{player}'], [$faction, $prefix?->getFormat() ?? '', $rank->getFormat(), $player->getName()], $rank->getNametagFormat());
    }

    public function getRank(): Rank {
        return $this->rank;
    }

    public function getPrefix(): ?Prefix {
        return $this->prefix;
    }

    public function getPlayer(): ?Player {
        return BetterRanks::getInstance()->getServer()->getPlayerByRawUUID($this->uuid);
    }

    public function isOnline(): bool {
        return $this->getPlayer() !== null;
    }

    public function setName(string $name): void {
        $this->name = $name;

        MySQL::runAsync(new UpdateAsync('ranks', ['name' => $this->getName(),], ['xuid' => $this->getXuid()]));
    }

    public function setRank(Rank $rank): void {
        $this->rank = $rank;

        MySQL::runAsync(new UpdateAsync('ranks', ['rank_name' => $rank->getName()], ['xuid' => $this->getXuid()]));
    }

    public function setPrefix(?Prefix $prefix): void {
        $this->prefix = $prefix;

        MySQL::runAsync(new UpdateAsync('ranks', ['prefix_name' => $prefix?->getName() ?? 'default'], ['xuid' => $this->getXuid()]));
    }

    public function updatePermissions(Player $player): void {
        $rank = $this->getRank();
        $prefix = $this->getPrefix();

        $permissions = array_merge($rank->getPermissions(), $prefix?->getPermissions() ?? []);

        if (count($permissions) !== 0) {
            foreach ($player->getEffectivePermissions() as $permission) {
                $attachment = $permission->getAttachment();

                if ($attachment !== null) {
                    $player->removeAttachment($attachment);
                }
            }

            foreach ($permissions as $permission) {
                $player->addAttachment(BetterRanks::getInstance(), $permission, true);
            }
        }
    }
}