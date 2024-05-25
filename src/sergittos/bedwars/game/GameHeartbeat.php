<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game;


use pocketmine\scheduler\Task;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\stage\PlayingStage;

class GameHeartbeat extends Task {

    private int $currentTick = 0;

    public function onRun(): void {
        $this->currentTick++;

        foreach(BedWars::getInstance()->getGameManager()->getGames() as $game) {
            if($game->getStage() instanceof PlayingStage) {
                $game->tickGenerators();
            }

            if($this->currentTick % 20 === 0) {
                $game->getStage()->tick();
            }
        }
    }

}