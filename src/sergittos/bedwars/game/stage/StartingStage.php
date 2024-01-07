<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\stage;


use pocketmine\world\sound\ClickSound;
use sergittos\bedwars\game\stage\trait\JoinableTrait;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\GameUtils;
use function str_replace;

class StartingStage extends Stage {
    use JoinableTrait {
        onJoin as onSessionJoin;
        onQuit as onSessionQuit;
    }

    private int $time = 10;

    public function getTime(): int {
        return $this->time;
    }

    public function onJoin(Session $session): void {
        if(!$this->justStarted()) {
            $this->onSessionJoin($session);
        }
    }

    public function onQuit(Session $session): void {
        $this->onSessionQuit($session);

        if(!$this->game->isFull()) {
            $this->game->setStage(new WaitingStage());
        }
    }

    public function tick(): void {
        if($this->time <= 0) {
            $this->game->setStage(new PlayingStage());
        } elseif($this->time <= 5) {
            $this->broadcastCountdownTitle();
        }
        if($this->time > 0) {
            $this->game->broadcastMessage($this->getStartingMessage());
        }
        if($this->time === 5) {
            $this->game->setupWorld();
        } elseif($this->time === 10) {
            $this->broadcastCountdownTitle();
        }
        $this->game->updateScoreboards();

        $this->time--;
    }

    private function getStartingMessage(): string {
        $message = "{YELLOW}The game is starting within {time} {YELLOW}seconds!";
        if($this->time <= 10) {
            $message = "{YELLOW}The game starts in {time} {YELLOW}" . ($this->time === 1 ? "second" : "seconds") . "!";
        }
        $message = str_replace("{time}", GameUtils::getColoredMessageNumber($this->time), $message);

        return $message;
    }

    private function broadcastCountdownTitle(): void {
        $this->game->broadcastTitle(GameUtils::getColoredTitleNumber($this->time));
        $this->game->broadcastSound(new ClickSound());
    }

    private function justStarted(): bool {
        return $this->time === 10;
    }

}