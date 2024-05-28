<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\map;


enum Mode: int {

    case SOLOS = 1;
    case DUOS = 2;
    case TRIOS = 3;
    case SQUADS = 4;

    public function getDisplayName(): string {
        return match($this) {
            self::SOLOS => "Solo",
            self::DUOS => "Duos",
            self::TRIOS => "Trios",
            self::SQUADS => "Squads"
        };
    }

}