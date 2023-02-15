<?php

declare(strict_types=1);

namespace juqn\betterranks\form\prefix;

use cosmicpe\form\CustomForm;
use cosmicpe\form\entries\custom\InputEntry;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class PrefixManageUserForm extends CustomForm {

    public function __construct() {
        parent::__construct('Prefix Manage User');

        $this->addEntry(new InputEntry(TextFormat::colorize('&7Player Username')), function (Player $player, InputEntry $entry, string $value): void {
            $target = $player->getServer()->getPlayerByPrefix($value);

            if ($target instanceof Player) {
                $xuid = $target->getXuid();
                $player->sendForm(new PrefixUserMenuForm($xuid));
            }
        });
    }
}