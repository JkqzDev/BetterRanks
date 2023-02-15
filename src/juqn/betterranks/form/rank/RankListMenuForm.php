<?php

declare(strict_types=1);

namespace juqn\betterranks\form\rank;

use cosmicpe\form\entries\simple\Button;
use cosmicpe\form\SimpleForm;
use juqn\betterranks\rank\Rank;
use juqn\betterranks\rank\RankFactory;
use pocketmine\utils\TextFormat;

final class RankListMenuForm extends SimpleForm {

    public function __construct() {
        parent::__construct('Rank list');
        $ranks = RankFactory::getAll();
        uasort($ranks, fn(Rank $firstRank, Rank $secondRank) => $firstRank->getPriority() <=> $secondRank->getPriority());

        foreach ($ranks as $rank) {
            $this->addButton(new Button(TextFormat::colorize($rank->getFormat() . PHP_EOL . '&fExample: ' . $rank->getFormat() . ' &7JuqnGOOD')));
        }
    }
}