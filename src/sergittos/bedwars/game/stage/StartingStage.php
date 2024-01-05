<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\stage;


use pocketmine\world\sound\ClickSound;
use sergittos\bedwars\session\scoreboard\WaitingScoreboard;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ConfigGetter;
use sergittos\bedwars\utils\GameUtils;
use function str_replace;
use function strtoupper;

class StartingStage extends Stage {

    private int $time = 10;

    public function getTime(): int {
        return $this->time;
    }

    public function onJoin(Session $session): void {
        $session->showBossBar("{YELLOW}Playing {WHITE}BED WARS {YELLOW}on {GREEN}" . strtoupper(ConfigGetter::getIP()));
        $session->getPlayer()->getEffects()->clear();
        $session->giveWaitingItems();
        $session->setGame($this->game);
        $session->setScoreboard(new WaitingScoreboard());
        $session->teleportToWaitingWorld();

        $this->game->broadcastMessage(
            "{GRAY}" . $session->getUsername() . " {YELLOW}has joined ({AQUA}" .
            $this->game->getPlayersCount() . "{YELLOW}/{AQUA}" . $this->game->getMap()->getMaxCapacity() . "{YELLOW})!"
        );
    }

    public function onQuit(Session $session): void {
        $this->game->broadcastMessage("{GRAY}" . $session->getUsername() . " {YELLOW}has quit!");

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

}