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

    private float $start_time = 0;
    private int $countdown = 10;

    public function getCountdown(): int {
        return $this->countdown;
    }

    protected function onStart(): void {
        $this->start_time = microtime(true);
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

    private function justStarted(): bool {
        return microtime(true) - $this->start_time < 0.01;
    }

}