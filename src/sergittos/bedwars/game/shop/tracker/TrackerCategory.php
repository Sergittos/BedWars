<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\tracker;


use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;
use function array_map;

class TrackerCategory extends Category {

    public function __construct() {
        parent::__construct("Tracker Shop");
    }

    /**
     * @return TrackerProduct[]
     */
    public function getProducts(Session $session): array {
        return array_map(function(Team $team) {
            return new TrackerProduct($name = $team->getName(), "Track Team " . $name);
        }, $session->getGame()->getTeams());
    }

}