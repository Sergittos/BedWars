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
use sergittos\bedwars\game\stage\EndingStage;

class EndGameInATieEvent extends Event {

    public function __construct() {
        parent::__construct("Tie", 10);
    }

    public function end(): void {
        $this->game->setStage(new EndingStage());
    }

    public function getNextEvent(): ?Event {
        return null;
    }

}