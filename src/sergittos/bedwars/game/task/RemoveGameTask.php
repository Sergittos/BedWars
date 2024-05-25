<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\task;


use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Filesystem;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\Game;

class RemoveGameTask extends AsyncTask {

    private int $id;

    private string $worldPath;

    public function __construct(Game $game) {
        $this->id = $game->getId();

        $this->worldPath = Server::getInstance()->getDataPath() . "worlds/" . $game->getMap()->getName() . "-" . $this->id;
    }

    public function onRun(): void {
        Filesystem::recursiveUnlink($this->worldPath);
    }

    public function onCompletion(): void {
        BedWars::getInstance()->getGameManager()->removeGame($this->id);
    }

}