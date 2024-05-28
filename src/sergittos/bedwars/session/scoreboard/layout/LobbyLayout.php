<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\scoreboard\layout;


use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\message\MessageContainer;
use function date;

class LobbyLayout implements Layout {

    public function getMessageContainer(Session $session): MessageContainer {
        return new MessageContainer("LOBBY_SCOREBOARD", [
            "date" => date("m/d/y"),
            "coins" => $session->getCoins(),
            "kills" => $session->getKills(),
            "wins" => $session->getWins()
        ]);
    }

}