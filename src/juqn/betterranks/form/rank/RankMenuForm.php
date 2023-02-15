<?php

declare(strict_types=1);

namespace juqn\betterranks\form\rank;

use cosmicpe\form\entries\simple\Button;
use cosmicpe\form\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class RankMenuForm extends SimpleForm {

    public function __construct() {
        parent::__construct('Rank Menu', 'Select a option!');
        $buttons = [
            '&fRank List' => new RankListMenuForm(),
            '&fManage User' => new RankManageUserForm()
        ];

        foreach ($buttons as $title => $form) {
            $this->addButton(
                new Button(TextFormat::colorize($title)),
                fn(Player $player, int $button_index) => $player->sendForm($form)
            );
        }
    }
}