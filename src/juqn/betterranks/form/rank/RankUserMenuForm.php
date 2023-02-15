<?php

declare(strict_types=1);

namespace juqn\betterranks\form\rank;

use cosmicpe\form\entries\simple\Button;
use cosmicpe\form\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class RankUserMenuForm extends SimpleForm {

    public function __construct(string $xuid) {
        parent::__construct('User Rank Menu');
        $buttons = [
            '&aSet Rank' => new RankSetUserForm($xuid),
            '&cRemove Rank' => new RankRemoveUserForm($xuid),
            '&4Back' => new RankManageUserForm(),
        ];

        foreach ($buttons as $title => $form) {
            $this->addButton(
                new Button(TextFormat::colorize($title)),
                fn(Player $player, int $button_index) => $player->sendForm($form)
            );
        }
    }
}