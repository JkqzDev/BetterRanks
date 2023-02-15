<?php

declare(strict_types=1);

namespace juqn\betterranks;

use juqn\betterranks\command\ListCommand;
use juqn\betterranks\command\PrefixCommand;
use juqn\betterranks\command\RankCommand;
use juqn\betterranks\database\mysql\MySQL;
use juqn\betterranks\database\mysql\Tables;
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
        MySQL::run(Tables::CREATE_RANK_TABLE);
    }

    protected function onEnable(): void {
        PrefixFactory::loadAll();
        RankFactory::loadAll();

        $this->checkConfigVersion();
        $this->checkExtensions();

        $this->unregisterCommands();

        $this->registerCommands();
        $this->registerHandler();
    }

    private function saveResources(): void {
        $this->saveDefaultConfig();
        $this->saveResource('prefixes.yml');
        $this->saveResource('ranks.yml');
    }

    private function checkConfigVersion(): void {
        if ($this->getConfig()->get('config-version', 1.0) !== 1.0) {
            $this->getLogger()->error('Plugin version invalid!');
            sleep(1);
            $this->getServer()->shutdown();
        }
    }

    private function checkExtensions(): void {
        if ($this->getConfig()->get('plugin-extension.enable')) {
            $kitMap_plugin = $this->getServer()->getPluginManager()->getPlugin($this->getConfig()->get('plugin-extension.kitmap', 'KitMap'));

            if (!$kitMap_plugin->isEnabled()) {
                $this->getLogger()->error('Plugin KitMap not exists in the server.');
                sleep(1);
                $this->getServer()->shutdown();
            }
        }
    }

    private function unregisterCommands(): void {
        $commands = [
            'list'
        ];

        foreach ($commands as $commandName) {
            $command = $this->getServer()->getCommandMap()->getCommand($commandName);

            if ($command !== null) {
                $this->getServer()->getCommandMap()->unregister($command);
            }
        }
    }

    private function registerCommands(): void {
        $commands = [
            new ListCommand,
            new PrefixCommand,
            new RankCommand
        ];
        $this->getServer()->getCommandMap()->registerAll('Ranks', $commands);
    }

    private function registerHandler(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventHandler(), $this);
    }
}

