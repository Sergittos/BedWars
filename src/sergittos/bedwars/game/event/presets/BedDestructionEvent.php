<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\event\presets;


use sergittos\bedwars\game\event\Event;

class BedDestructionEvent extends Event {

    public function __construct() {
        parent::__construct("Bed destruction", 6);
    }

    public function end(): void {
        foreach($this->game->getTeams() as $team) {
            if(!$team->isBedDestroyed()) {
                $team->destroyBed($this->game);
            }
        }
        $this->game->updateScoreboards();
    }

    public function getNextEvent(): Event {
        return new EndGameInATieEvent();
    }

}