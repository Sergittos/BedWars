<?php

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

    private string $mapId;
    private string $mapName;

    private string $worldPath;
    private string $destinationPath;

    public function __construct(int $id, Map $map) {
        $this->id = $id;

        $this->mapId = $map->getId();
        $this->mapName = $map->getName();

        $this->worldPath = BedWars::getInstance()->getDataFolder() . "worlds/" . $this->mapName;
        $this->destinationPath = Server::getInstance()->getDataPath() . "worlds/" . $this->mapName . "-" . $id;
    }

    public function onRun(): void {
        Filesystem::recursiveCopy($this->worldPath, $this->destinationPath);
    }

    public function onCompletion(): void {
        BedWars::getInstance()->getGameManager()->addGame(new Game(MapFactory::getMapById($this->mapId), $this->id));
    }

}