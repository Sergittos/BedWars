<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\session\scoreboard;


use sergittos\bedwars\game\Game;
use sergittos\bedwars\game\stage\StartingStage;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ConfigGetter;
use sergittos\bedwars\utils\GameUtils;
use function strtolower;

class WaitingScoreboard extends Scoreboard {

    protected function getLines(Session $session): array {
        $game = $session->getGame();
        $arena = $game->getMap();
        $stage = $game->getStage();
        return [
            10 => " ",
            9 => "{WHITE}Map: {GREEN}" . $arena->getName(),
            8 => "{WHITE}Players: {GREEN}" . $game->getPlayersCount() . "/" . $arena->getMaxCapacity(),
            7 => "  ",
            6 => !$stage instanceof StartingStage ? "{WHITE}Waiting..." : "{WHITE}Starting in {GREEN}" . $stage->getTime() . "s",
            5 => "   ",
            4 => "{WHITE}Mode: {GREEN}" . GameUtils::getMode($arena->getPlayersPerTeam()),
            3 => "{WHITE}Version: {GRAY}v" . ConfigGetter::getVersion()
        ];
    }

}