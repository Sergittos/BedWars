<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\stage;


use sergittos\bedwars\game\Game;
use sergittos\bedwars\session\scoreboard\WaitingScoreboard;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ConfigGetter;
use function strtoupper;

class WaitingStage extends Stage {

    public function start(Game $game): void {
        $this->game = $game;
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

        $map = $this->game->getMap();
        $count = $this->game->getPlayersCount();

        if($count > $map->getPlayersPerTeam() and $count >= ($map->getMaxCapacity() / 2)) {
            $this->game->setStage(new StartingStage());
        }
    }

    public function tick(): void {}

}