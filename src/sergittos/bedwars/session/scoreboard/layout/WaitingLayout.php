<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\scoreboard\layout;


use sergittos\bedwars\game\stage\StartingStage;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ConfigGetter;
use sergittos\bedwars\utils\GameUtils;
use sergittos\bedwars\utils\message\MessageContainer;
use function date;

class WaitingLayout implements Layout {

    public function getMessageContainer(Session $session): MessageContainer {
        $game = $session->getGame();
        $map = $game->getMap();
        $stage = $game->getStage();

        return new MessageContainer("WAITING_SCOREBOARD", [
            "date" => date("m/d/y"),
            "players_count" => $game->getPlayersCount(),
            "slots" => $map->getMaxCapacity(),
            "stage" => !$stage instanceof StartingStage ? new MessageContainer("WAITING_STAGE") : new MessageContainer("STARTING_STAGE", ["time" => $stage->getCountdown()]),
            "map" => $map->getName(),
            "mode" => GameUtils::getMode($map->getPlayersPerTeam()),
            "version" => ConfigGetter::getVersion()
        ]);
    }

}