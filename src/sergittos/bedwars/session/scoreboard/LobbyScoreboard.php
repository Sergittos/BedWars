<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\scoreboard;


use sergittos\bedwars\session\Session;

class LobbyScoreboard extends Scoreboard {

    protected function getLines(Session $session): array {
        return [
            13 => " ",
            12 => "{WHITE}Your level: {GOLD}0",
            11 => "  ",
            10 => "{WHITE}Progress: {AQUA}0k{GRAY}/{GREEN}0k",
            9 => "   ",
            8 => "{WHITE}Loot chests: {GOLD}0",
            7 => "    ",
            6 => "{WHITE}Coins: {GREEN}" . $session->getCoins(),
            5 => "     ",
            4 => "{WHITE}Total Kills: {GREEN}" . $session->getKills(),
            3 => "{WHITE}Total Wins: {GREEN}" . $session->getWins(),
        ];
    }

}