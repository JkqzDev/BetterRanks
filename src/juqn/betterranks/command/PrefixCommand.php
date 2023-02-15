<?php

declare(strict_types=1);

namespace juqn\betterranks\command;

use juqn\betterranks\form\prefix\PrefixMenuForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

final class PrefixCommand extends Command {

    public function __construct() {
        parent::__construct('prefix', 'Use command for prefixes');
        $this->setPermission('prefix.command');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) {
            return;
        }

        if (!$sender instanceof Player) {
            return;
        }
        $sender->sendForm(new PrefixMenuForm());
    }
}