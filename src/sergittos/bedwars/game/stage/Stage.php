<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\stage;


use sergittos\bedwars\game\Game;
use sergittos\bedwars\session\Session;

abstract class Stage {

    protected Game $game;

    public function start(Game $game): void {
        $this->game = $game;
        $this->onStart();

        foreach($this->game->getPlayers() as $session) {
            $this->onJoin($session);
        }
    }

    protected function onStart(): void {}

    /*
     * Called when someone joins the game in this stage
     */
    public function onJoin(Session $session): void {}

    /*
     * Called when someone quits the game in this stage
     */
    public function onQuit(Session $session): void {}

    abstract public function tick(): void;

}