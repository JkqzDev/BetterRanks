<?php

declare(strict_types=1);

namespace juqn\betterranks\form\prefix;

use cosmicpe\form\entries\simple\Button;
use cosmicpe\form\SimpleForm;
use juqn\betterranks\prefix\PrefixFactory;
use pocketmine\utils\TextFormat;

final class PrefixListMenuForm extends SimpleForm {

    public function __construct() {
        parent::__construct('Rank list');
        $prefixes = PrefixFactory::getAll();

        foreach ($prefixes as $prefix) {
            $this->addButton(new Button(TextFormat::colorize($prefix->getFormat() . PHP_EOL . '&fExample: ' . $prefix->getFormat() . ' &7JuqnGOOD')));
        }
    }
}