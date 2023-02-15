<?php

declare(strict_types=1);

namespace juqn\betterranks\form\rank;

use cosmicpe\form\CustomForm;
use cosmicpe\form\entries\simple\Button;
use cosmicpe\form\SimpleForm;
use juqn\betterranks\rank\Rank;
use juqn\betterranks\rank\RankFactory;
use juqn\betterranks\session\Session;
use juqn\betterranks\session\SessionFactory;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class RankSetUserForm extends SimpleForm {

    public function __construct(string $xuid, ?string $name = null) {
        parent::__construct('Set Rank to User');
        $session = SessionFactory::get($xuid);

        if ($session !== null) {
            $ranks = RankFactory::getAll();
            uasort($ranks, fn(Rank $firstRank, Rank $secondRank) => $firstRank->getPriority() <=> $secondRank->getPriority());

            foreach ($ranks as $rank) {
                $this->addButton(
                    new Button(TextFormat::colorize($rank->getFormat() . PHP_EOL . '&fExample: ' . $rank->getFormat() . ' &7JuqnGOOD')),
                    fn(Player $player, int $button_index) => $session->setRank($rank)
                );
            }
        }
    }
}