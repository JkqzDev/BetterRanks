<?php

declare(strict_types=1);

namespace juqn\betterranks\form\prefix;

use cosmicpe\form\entries\simple\Button;
use cosmicpe\form\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class PrefixUserMenuForm extends SimpleForm {

    public function __construct(string $xuid, ?string $name = null) {
        parent::__construct('User Prefix Menu');

        $buttons = [
            '&aSet Prefix' => new PrefixSetUserForm($xuid, $name),
            '&cRemove Prefix' => new PrefixRemoveUserForm($xuid, $name),
            '&4Back' => new PrefixManageUserForm(),
        ];

        foreach ($buttons as $title => $form) {
            $this->addButton(
                new Button(TextFormat::colorize($title)),
                fn(Player $player, int $button_index) => $player->sendForm($form)
            );
        }
    }
}