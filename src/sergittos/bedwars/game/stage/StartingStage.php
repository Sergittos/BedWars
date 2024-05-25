<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\stage;


use pocketmine\world\sound\ClickSound;
use sergittos\bedwars\game\stage\trait\JoinableTrait;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\GameUtils;
use function count;
use function str_replace;

class StartingStage extends Stage {
    use JoinableTrait {
        onQuit as onSessionQuit;
    }

    private int $countdown = 10;

    public function getCountdown(): int {
        return $this->countdown;
    }

    public function onQuit(Session $session): void {
        $this->onSessionQuit($session);

        if(count($this->game->getPlayers()) < ($this->game->getMap()->getMaxCapacity() / 2)) {
            $this->game->setStage(new WaitingStage());
        }
    }

    public function tick(): void {
        if($this->countdown <= 0) {
            $this->game->setStage(new PlayingStage());
        } elseif($this->countdown <= 5) {
            $this->broadcastCountdownTitle();
        }
        if($this->countdown > 0) {
            $this->game->broadcastMessage($this->getStartingMessage());
        }
        if($this->countdown === 5) {
            $this->game->setupWorld();
        } elseif($this->countdown === 10) {
            $this->broadcastCountdownTitle();
        }
        $this->game->updateScoreboards();

        $this->countdown--;
    }

    private function getStartingMessage(): string {
        $message = "{YELLOW}The game is starting within {time} {YELLOW}seconds!";
        if($this->countdown <= 10) {
            $message = "{YELLOW}The game starts in {time} {YELLOW}" . ($this->countdown === 1 ? "second" : "seconds") . "!";
        }
        $message = str_replace("{time}", GameUtils::getColoredMessageNumber($this->countdown), $message);

        return $message;
    }

    private function broadcastCountdownTitle(): void {
        $this->game->broadcastTitle(GameUtils::getColoredTitleNumber($this->countdown));
        $this->game->broadcastSound(new ClickSound());
    }

}