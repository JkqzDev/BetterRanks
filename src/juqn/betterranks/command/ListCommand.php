<?php

declare(strict_types=1);

namespace juqn\betterranks\command;

use juqn\betterranks\BetterRanks;
use juqn\betterranks\rank\Rank;
use juqn\betterranks\rank\RankFactory;
use juqn\betterranks\session\Session;
use juqn\betterranks\session\SessionFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\utils\TextFormat;

final class ListCommand extends Command {

    public function __construct() {
        parent::__construct('list', KnownTranslationFactory::pocketmine_command_list_description());
        $this->setPermission(DefaultPermissionNames::COMMAND_LIST);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) {
            return;
        }
        $ranks = RankFactory::getAll();
        $sessions = array_filter(SessionFactory::getAll(), fn(Session $session) => $session->isOnline());

        uasort($ranks, fn(Rank $firstRank, Rank $secondRank) => $firstRank->getPriority() <=> $secondRank->getPriority());
        uasort($sessions, fn(Session $firstSession, Session $secondSession) => $firstSession->getRank()->getPriority() <=> $secondSession->getRank()->getPriority());

        $sender->sendMessage(TextFormat::colorize('&c'));
        $sender->sendMessage(TextFormat::colorize(implode(', ', array_map(fn(Rank $rank) => '&e' . $rank->getName(), $ranks))));
        $sender->sendMessage(TextFormat::colorize(
            '&7(&6' . count($sessions) . '&7/&6' . $sender->getServer()->getMaxPlayers() . ' &7) [' .
            implode(', ', array_map(fn(Session $session) => ($session->getRank()->getName() === BetterRanks::getInstance()->getConfig()->get('rank-default', 'user') ? '&f' : $session->getRank()->getFormat() . ' ') . $session->getName(), $sessions))
        )) . '&7]';
        $sender->sendMessage(TextFormat::colorize('&c'));
    }
}