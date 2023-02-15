<?php

declare(strict_types=1);

namespace juqn\betterranks\form\rank;

use cosmicpe\form\ModalForm;
use juqn\betterranks\BetterRanks;
use juqn\betterranks\rank\RankFactory;
use juqn\betterranks\session\SessionFactory;
use pocketmine\player\Player;

final class RankRemoveUserForm extends ModalForm {

    public function __construct(private string $xuid, private ?string $name = null) {
        parent::__construct('Remove Rank To User', 'Are you sure that you want to remove User\'s rank? His/her rank will be converted to the default rank');

        $this->setFirstButton('Sure!');
        $this->setSecondButton('Nop.');
    }

    protected function onAccept(Player $player): void {
        $session = SessionFactory::get($this->xuid);

        if ($session !== null) {
            $session->setRank(RankFactory::get(BetterRanks::getInstance()->getConfig()->get('rank-default', 'user')));
        }
    }

    protected function onClose(Player $player): void {
        $player->sendForm(new RankManageUserForm());
    }
}