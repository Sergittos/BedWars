<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\stage;


use sergittos\bedwars\session\Session;
use function count;

class EndingStage extends Stage {

    private int $time = 5;

    private bool $tie = false;

    protected function onStart(): void {
        foreach($this->game->getSpectators() as $session) {
            $session->title("{RED}GAME OVER!");
        }
        $this->tie = count($this->game->getAliveTeams()) > 1;
    }

    public function onJoin(Session $session): void {
        if(!$this->tie) {
            $session->addWin();
            $session->addCoins(140);
        }

        $session->title($this->getTitle(), "", 0, $this->time * 20);
        $session->resetSettings();
    }

    public function tick(): void {
        $this->time--;
        if($this->time <= 0) {
            $this->game->reset();
        }
    }

    private function getTitle(): string {
        return $this->tie ? "{ORANGE}TIE!" : "{GOLD}VICTORY!";
    }

}