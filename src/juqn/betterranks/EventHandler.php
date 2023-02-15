<?php

declare(strict_types=1);

namespace juqn\betterranks;

use juqn\betterranks\session\SessionFactory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\TextFormat;

final class EventHandler implements Listener {

    public function handleChat(PlayerChatEvent $event): void {
        $message = $event->getMessage();
        $player = $event->getPlayer();
        $session = SessionFactory::get($player);

        if ($session === null) {
            return;
        }

        if (BetterRanks::getInstance()->getConfig()->get('rank-chat.format', true)) {
            $event->setFormat(TextFormat::colorize($session->getChatFormat($player, $message)));
        }
    }

    public function handleJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $session = SessionFactory::get($player);

        if ($session === null) {
            SessionFactory::create($player);
            return;
        }

        if (BetterRanks::getInstance()->getConfig()->get('rank-nametag.format', false)) {
            $player->setNameTag(TextFormat::colorize($session->getNametagFormat($player)));
        }
    }
}