<?php

declare(strict_types=1);

namespace juqn\betterranks;

use juqn\betterranks\database\mysql\MySQL;
use juqn\betterranks\prefix\PrefixFactory;
use juqn\betterranks\rank\RankFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

final class BetterRanks extends PluginBase {
    use SingletonTrait;

    protected function onLoad(): void {
        self::setInstance($this);
        $this->saveResources();

        MySQL::setCredentials($this->getConfig()->get('mysql', []));
    }

    protected function onEnable(): void {
        PrefixFactory::loadAll();
        RankFactory::loadAll();

        $this->registerHandler();
    }

    private function saveResources(): void {
        $this->saveDefaultConfig();
        $this->saveResource('prefixes.yml');
        $this->saveResource('ranks.yml');
    }

    private function registerHandler(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler(), $this);
    }
}

