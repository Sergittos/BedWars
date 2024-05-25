<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars;


use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\event\Listener;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use sergittos\bedwars\command\BedWarsCommand;
use sergittos\bedwars\entity\PlayBedwarsEntity;
use sergittos\bedwars\game\entity\misc\Fireball;
use sergittos\bedwars\game\entity\shop\ItemShopVillager;
use sergittos\bedwars\game\entity\shop\UpgradesShopVillager;
use sergittos\bedwars\game\GameHeartbeat;
use sergittos\bedwars\game\GameManager;
use sergittos\bedwars\game\map\MapFactory;
use sergittos\bedwars\listener\GameListener;
use sergittos\bedwars\listener\ItemListener;
use sergittos\bedwars\listener\SessionListener;
use sergittos\bedwars\listener\SetupListener;
use sergittos\bedwars\listener\SpawnProtectionListener;
use sergittos\bedwars\listener\WaitingListener;
use sergittos\bedwars\provider\json\JsonProvider;
use sergittos\bedwars\provider\mysql\MysqlProvider;
use sergittos\bedwars\provider\Provider;
use sergittos\bedwars\provider\sqlite\SqliteProvider;
use sergittos\bedwars\session\SessionFactory;
use sergittos\bedwars\utils\ConfigGetter;
use function basename;
use function strtolower;

class BedWars extends PluginBase {
    use SingletonTrait;

    private Provider $provider;
    private GameManager $gameManager;

    protected function onLoad(): void {
        self::setInstance($this);

        $worldsDir = $this->getDataFolder() . "worlds/";
        if(!is_dir($worldsDir)) {
            mkdir($worldsDir);
        }
    }

    protected function onEnable(): void {
        MapFactory::init();

        $this->provider = $this->obtainProvider();
        $this->gameManager = new GameManager();

        $this->registerEntity(PlayBedwarsEntity::class);
        $this->registerEntity(ItemShopVillager::class);
        $this->registerEntity(UpgradesShopVillager::class);
        $this->registerFireball();

        $this->registerListener(new GameListener());
        $this->registerListener(new ItemListener());
        $this->registerListener(new SessionListener());
        $this->registerListener(new SetupListener());
        $this->registerListener(new WaitingListener());

        if(ConfigGetter::isSpawnProtectionEnabled()) {
            $this->registerListener(new SpawnProtectionListener());
        }

        $this->getServer()->getCommandMap()->register("bedwars", new BedWarsCommand());

        $this->getScheduler()->scheduleRepeatingTask(new GameHeartbeat(), 1);
    }

    protected function onDisable(): void {
        foreach($this->gameManager->getGames() as $game) {
            $game->reset();
        }

        foreach(SessionFactory::getSessions() as $session) {
            $session->save();
        }
    }

    private function registerListener(Listener $listener): void {
        $this->getServer()->getPluginManager()->registerEvents($listener, $this);
    }

    private function registerEntity(string $class): void {
        EntityFactory::getInstance()->register($class, function(World $world, CompoundTag $nbt) use ($class): Entity {
            return new $class(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
        }, ["bedwars:" . basename($class)]);
    }

    private function registerFireball(): void {
        EntityFactory::getInstance()->register(Fireball::class, function(World $world, CompoundTag $nbt): Fireball {
            return new Fireball(EntityDataHelper::parseLocation($nbt, $world), null);
        }, ["bedwars:fireball"]);
    }

    private function obtainProvider(): Provider {
        return match(strtolower(ConfigGetter::getProvider())) {
            "mysql" => new MysqlProvider(),
            "sqlite", "sqlite3" => new SqliteProvider(),
            "json" => new JsonProvider(),
            default => throw new \Error("Invalid provider, check your config and try again.")
        };
    }

    public function getProvider(): Provider {
        return $this->provider;
    }

    public function getGameManager(): GameManager {
        return $this->gameManager;
    }

}