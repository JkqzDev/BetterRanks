<?php

declare(strict_types=1);

namespace juqn\betterranks\form\rank;

use cosmicpe\form\ModalForm;
use juqn\betterranks\BetterRanks;
use juqn\betterranks\database\mysql\MySQL;
use juqn\betterranks\database\mysql\query\UpdateAsync;
use juqn\betterranks\rank\RankFactory;
use juqn\betterranks\session\SessionFactory;
use pocketmine\player\Player;

final class RankRemoveUserForm extends ModalForm {

    public function __construct(private string $xuid) {
        parent::__construct('Remove Rank To User', 'Are you sure you want to remove the player rank?');

        $this->setFirstButton('Sure!');
        $this->setSecondButton('Nop.');
    }

    protected function onAccept(Player $player): void {
        $session = SessionFactory::get($this->xuid);

        if ($session !== null) {
            $session->setRank(RankFactory::get(BetterRanks::getInstance()->getConfig()->get('rank-default', 'user')));
        } else {
            MySQL::runAsync(new UpdateAsync('ranks', ['rank_name' => BetterRanks::getInstance()->getConfig()->get('rank-default', 'user')], ['xuid' => $this->xuid]));
        }
    }

    protected function onClose(Player $player): void {
        $player->sendForm(new RankManageUserForm());
    }
}