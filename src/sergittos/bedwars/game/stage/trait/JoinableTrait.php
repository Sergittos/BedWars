<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace sergittos\bedwars\game\stage\trait;


use sergittos\bedwars\game\Game;
use sergittos\bedwars\session\scoreboard\layout\WaitingLayout;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ConfigGetter;
use function strtoupper;

trait JoinableTrait {

    public function start(Game $game): void {
        $this->game = $game;
    }

    public function onJoin(Session $session): void {
        $session->showBossBar("{YELLOW}Playing {WHITE}BED WARS {YELLOW}on {GREEN}" . strtoupper(ConfigGetter::getIP()));
        $session->getPlayer()->getEffects()->clear();
        $session->giveWaitingItems();
        $session->setGame($this->game);
        $session->setScoreboardLayout(new WaitingLayout());
        $session->teleportToWaitingWorld();

        $this->game->broadcastMessage(
            "{GRAY}" . $session->getUsername() . " {YELLOW}has joined ({AQUA}" .
            $this->game->getPlayersCount() . "{YELLOW}/{AQUA}" . $this->game->getMap()->getMaxCapacity() . "{YELLOW})!"
        );
    }

    public function onQuit(Session $session): void {
        $this->game->broadcastMessage("{GRAY}" . $session->getUsername() . " {YELLOW}has quit!");

        if($this->game->getPlayersCount() === 0) {
            $this->game->reset();
        }
    }

}