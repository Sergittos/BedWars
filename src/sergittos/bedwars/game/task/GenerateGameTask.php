<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\task;


use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Filesystem;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\map\Map;
use sergittos\bedwars\game\map\MapFactory;
use sergittos\bedwars\game\Game;

class GenerateGameTask extends AsyncTask {

    private int $id;

    private string $map_id;
    private string $map_name;

    private string $world_path;
    private string $destination_path;

    public function __construct(int $id, Map $map) {
        $this->id = $id;

        $this->map_id = $map->getId();
        $this->map_name = $map->getName();

        $this->world_path = BedWars::getInstance()->getDataFolder() . "worlds/" . $this->map_name;
        $this->destination_path = Server::getInstance()->getDataPath() . "worlds/" . $this->map_name . "-" . $id;
    }

    public function onRun(): void {
        Filesystem::recursiveCopy($this->world_path, $this->destination_path);
    }

    public function onCompletion(): void {
        BedWars::getInstance()->getGameManager()->addGame(new Game(MapFactory::getMap($this->map_id), $this->id));
    }

}