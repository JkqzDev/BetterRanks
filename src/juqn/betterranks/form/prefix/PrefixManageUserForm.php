<?php

declare(strict_types=1);

namespace juqn\betterranks\form\prefix;

use cosmicpe\form\CustomForm;
use cosmicpe\form\entries\custom\InputEntry;
use juqn\betterranks\database\mysql\MySQL;
use juqn\betterranks\database\mysql\query\SelectQuery;
use juqn\betterranks\form\rank\RankUserMenuForm;
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
            } else {
                MySQL::runAsync(new SelectQuery(
                    'ranks',
                    [
                        'name' => $value
                    ],
                    '',
                    function (array $rows) use ($player): void {
                        if (count($rows) === 0) {
                            $player->sendMessage(TextFormat::colorize('&cPlayer not found.'));
                        } else {
                            $data = $rows[0];

                            $player->sendForm(new PrefixUserMenuForm($data['xuid']));
                        }
                    }
                ));
            }
        });
    }
}