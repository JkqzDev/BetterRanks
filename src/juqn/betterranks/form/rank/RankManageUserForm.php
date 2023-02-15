<?php

declare(strict_types=1);

namespace juqn\betterranks\form\rank;

use cosmicpe\form\CustomForm;
use cosmicpe\form\entries\custom\InputEntry;
use juqn\betterranks\session\SessionFactory;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class RankManageUserForm extends CustomForm {

    public function __construct() {
        parent::__construct('Rank Manage User');

        $this->addEntry(new InputEntry(TextFormat::colorize('&7Player Username')), function (Player $player, InputEntry $entry, string $value): void {
            $target = $player->getServer()->getPlayerByPrefix($value);

            if ($target instanceof Player) {
                $xuid = $target->getXuid();
                $player->sendForm(new RankUserMenuForm($xuid));
            }
        });
    }
}