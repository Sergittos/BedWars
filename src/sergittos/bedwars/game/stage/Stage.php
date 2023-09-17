<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
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