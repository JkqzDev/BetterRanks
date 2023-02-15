<?php

declare(strict_types=1);

namespace juqn\betterranks\command;
use juqn\betterranks\form\rank\RankMenuForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class RankCommand extends Command {

    public function __construct() {
        parent::__construct('rank', 'Use command for ranks');
        $this->setPermission('rank.command');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) {
            return;
        }

        if (!$sender instanceof Player) {
            return;
        }
        $sender->sendForm(new RankMenuForm());
    }
}