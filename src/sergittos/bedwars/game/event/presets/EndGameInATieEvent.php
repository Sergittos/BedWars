<?php

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