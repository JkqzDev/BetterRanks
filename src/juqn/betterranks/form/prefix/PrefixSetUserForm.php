<?php

declare(strict_types=1);

namespace juqn\betterranks\form\prefix;

use cosmicpe\form\entries\simple\Button;
use cosmicpe\form\SimpleForm;
use juqn\betterranks\database\mysql\MySQL;
use juqn\betterranks\database\mysql\query\UpdateAsync;
use juqn\betterranks\prefix\PrefixFactory;
use juqn\betterranks\session\SessionFactory;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class PrefixSetUserForm extends SimpleForm {

    public function __construct(string $xuid) {
        parent::__construct('Set Prefix to User');
        $session = SessionFactory::get($xuid);
        $prefixes = PrefixFactory::getAll();

        if ($session !== null) {
            foreach ($prefixes as $prefix) {
                $this->addButton(
                    new Button(TextFormat::colorize($prefix->getFormat() . PHP_EOL . '&fExample: ' . $prefix->getFormat() . ' &7JuqnGOOD')),
                    fn(Player $player, int $button_index) => $session->setPrefix($prefix)
                );
            }
        } else {
            foreach ($prefixes as $prefix) {
                $this->addButton(
                    new Button(TextFormat::colorize($prefix->getFormat() . PHP_EOL . '&fExample: ' . $prefix->getFormat() . ' &7JuqnGOOD')),
                    fn(Player $player, int $button_index) => MySQL::runAsync(new UpdateAsync('ranks', ['prefix_name' => $prefix->getName()], ['xuid' => $xuid]))
                );
            }
        }
    }
}