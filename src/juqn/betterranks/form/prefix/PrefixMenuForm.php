<?php

declare(strict_types=1);

namespace juqn\betterranks\form\prefix;

use cosmicpe\form\entries\simple\Button;
use cosmicpe\form\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class PrefixMenuForm extends SimpleForm {

    public function __construct() {
        parent::__construct('Prefix Menu', 'Select a option!');
        $buttons = [
            '&fPrefix List' => new PrefixListMenuForm(),
            '&fManage User' => new PrefixManageUserForm()
        ];

        foreach ($buttons as $title => $form) {
            $this->addButton(
                new Button(TextFormat::colorize($title)),
                fn(Player $player, int $button_index) => $player->sendForm($form)
            );
        }
    }
}