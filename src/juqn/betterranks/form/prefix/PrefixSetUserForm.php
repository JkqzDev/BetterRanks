<?php

declare(strict_types=1);

namespace juqn\betterranks\form\prefix;

use cosmicpe\form\entries\simple\Button;
use cosmicpe\form\SimpleForm;
use juqn\betterranks\prefix\PrefixFactory;
use juqn\betterranks\session\SessionFactory;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class PrefixSetUserForm extends SimpleForm {

    public function __construct(string $xuid, ?string $name = null) {
        parent::__construct('Set Prefix to User');
        $session = SessionFactory::get($xuid);

        if ($session !== null) {
            $prefixes = PrefixFactory::getAll();

            foreach ($prefixes as $prefix) {
                $this->addButton(
                    new Button(TextFormat::colorize($prefix->getFormat() . PHP_EOL . '&fExample: ' . $prefix->getFormat() . ' &7JuqnGOOD')),
                    fn(Player $player, int $button_index) => $session->setPrefix($prefix)
                );
            }
        }
    }
}