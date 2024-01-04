<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\map\task;


use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Filesystem;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\map\MapFactory;
use sergittos\bedwars\session\setup\builder\MapBuilder;
use function file_get_contents;
use function file_put_contents;

class CreateMapTask extends AsyncTask {

    private string $world_path;
    private string $destination_path;

    public function __construct(MapBuilder $map) {
        $this->storeLocal("map", $map);

        $this->world_path = Server::getInstance()->getDataPath() . "worlds/" . $map->getPlayingWorld();
        $this->destination_path = BedWars::getInstance()->getDataFolder() . "worlds/" . $map->getName();

        $world_manager = Server::getInstance()->getWorldManager();
        $world_manager->unloadWorld($world_manager->getWorldByName($map->getPlayingWorld()));
    }

    public function onRun(): void {
        Filesystem::recursiveCopy($this->world_path, $this->destination_path);
    }

    public function onCompletion(): void { // TODO: Do not reuse code
        $map = $this->fetchLocal("map")->build();

        $plugin = BedWars::getInstance();
        $path = $plugin->getDataFolder() . "maps.json";

        $data = json_decode(file_get_contents($path), true);
        $data[] = $map->jsonSerialize();

        file_put_contents($path, json_encode($data, JSON_THROW_ON_ERROR));

        MapFactory::addMap($map);
        $plugin->getGameManager()->generateGames($map);
    }

}