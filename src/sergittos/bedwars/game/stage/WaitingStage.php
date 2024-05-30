<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\stage;


use sergittos\bedwars\game\stage\trait\JoinableTrait;
use sergittos\bedwars\session\Session;

class WaitingStage extends Stage {
    use JoinableTrait {
        onJoin as onSessionJoin;
    }

    public function onJoin(Session $session): void {
        $this->onSessionJoin($session);
        $this->game->setStage(new StartingStage());
    }

    private function startIfReady(): void {
        $map = $this->game->getMap();
        $count = $this->game->getPlayersCount();

        if($count > $map->getMode()->value and $count >= ($map->getMaxCapacity() / 2)) {
            $this->game->setStage(new StartingStage());
        }
    }

    public function tick(): void {}

}