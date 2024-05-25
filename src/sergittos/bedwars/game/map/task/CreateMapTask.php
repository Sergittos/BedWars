<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

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

    private string $worldPath;
    private string $destinationPath;

    public function __construct(MapBuilder $map) {
        $this->storeLocal("map", $map);

        $this->worldPath = Server::getInstance()->getDataPath() . "worlds/" . $map->getPlayingWorld();
        $this->destinationPath = BedWars::getInstance()->getDataFolder() . "worlds/" . $map->getName();

        $worldManager = Server::getInstance()->getWorldManager();
        $worldManager->unloadWorld($worldManager->getWorldByName($map->getPlayingWorld()));
    }

    public function onRun(): void {
        Filesystem::recursiveCopy($this->worldPath, $this->destinationPath);
    }

    public function onCompletion(): void { // TODO: Do not reuse code
        $map = $this->fetchLocal("map")->build();

        $plugin = BedWars::getInstance();
        $path = $plugin->getDataFolder() . "maps.json";

        $data = json_decode(file_get_contents($path), true);
        $data[] = $map->jsonSerialize();

        file_put_contents($path, json_encode($data, JSON_THROW_ON_ERROR));

        MapFactory::addMap($map);
    }

}