<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\scoreboard;


use sergittos\bedwars\session\Session;

class LobbyScoreboard extends Scoreboard {

    protected function getLines(Session $session): array {
        return [
            7 => "    ",
            6 => "{WHITE}Coins: {GREEN}" . $session->getCoins(),
            5 => "     ",
            4 => "{WHITE}Total Kills: {GREEN}" . $session->getKills(),
            3 => "{WHITE}Total Wins: {GREEN}" . $session->getWins(),
        ];
    }

}