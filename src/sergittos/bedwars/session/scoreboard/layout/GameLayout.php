<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\scoreboard\layout;


use sergittos\bedwars\game\stage\PlayingStage;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\message\MessageContainer;
use function array_map;
use function date;
use function gmdate;

class GameLayout implements Layout {

    public function getMessageContainer(Session $session): MessageContainer {
        $stage = $session->getGame()->getStage();

        $event = new MessageContainer("GAME_EVENT_ENDED");
        if($stage instanceof PlayingStage) {
            $event = new MessageContainer("GAME_EVENT", [
                "event" => $stage->getNextEvent()->getName(),
                "time" => gmdate("i:s", $stage->getNextEvent()->getTimeRemaining())
            ]);
        }

        return new MessageContainer("GAME_SCOREBOARD", [
            "date" => date("m/d/y"),
            "event" => $event,
            "teams" => array_map(fn(Team $team) => new MessageContainer("TEAM_SCOREBOARD", [
                "color" => $team->getColor(),
                "first_letter" => $team->getFirstLetter(),
                "name" => $team->getName(),
                "status" => $this->getBedStatus($team),
                "you" => ($team->hasMember($session) ? "{GRAY}YOU" : "")
            ]), $session->getGame()->getTeams()),
        ]);
    }

    private function getBedStatus(Team $team): MessageContainer {
        return !$team->isAlive() ?
            new MessageContainer("TEAM_STATUS_ELIMINATED") : (
                $team->isBedDestroyed() ?
                new MessageContainer("TEAM_STATUS_WITHOUT_BED", [
                    "players" => $team->getMembersCount()
                ]) :
                new MessageContainer("TEAM_STATUS_ALIVE")
            );
    }

}