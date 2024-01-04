<?php

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