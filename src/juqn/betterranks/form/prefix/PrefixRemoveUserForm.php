<?php

declare(strict_types=1);

namespace juqn\betterranks\form\prefix;

use cosmicpe\form\ModalForm;
use juqn\betterranks\database\mysql\MySQL;
use juqn\betterranks\database\mysql\query\UpdateAsync;
use juqn\betterranks\session\SessionFactory;
use pocketmine\player\Player;

final class PrefixRemoveUserForm extends ModalForm {

    public function __construct(private string $xuid) {
        parent::__construct('Remove Prefix To User', 'Are you sure that you want to remove User\'s prefix?');

        $this->setFirstButton('Sure!');
        $this->setSecondButton('Nop.');
    }

    protected function onAccept(Player $player): void {
        $session = SessionFactory::get($this->xuid);

        if ($session !== null) {
            $session->setPrefix(null);
        } else {
            MySQL::runAsync(new UpdateAsync('ranks', ['prefix_name' => 'default'], ['xuid' => $this->xuid]));
        }
    }

    protected function onClose(Player $player): void {
        $player->sendForm(new PrefixManageUserForm());
    }
}