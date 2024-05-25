<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\tracker;


use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\shop\Product;
use sergittos\bedwars\session\Session;
use function array_rand;

class TrackerProduct extends Product {

    public function __construct(string $id, string $name) {
        parent::__construct($id, $name, 2, VanillaItems::EMERALD());
    }

    public function onPurchase(Session $session): bool {
        $trackingTeam = null;

        $teams = $session->getGame()->getTeams();
        foreach($teams as $team) {
            if($team->getName() === $this->id) {
                $trackingTeam = $team;
            }

            if(!$team->isBedDestroyed()) {
                $session->message("{RED}Not all enemy beds are destroyed yet!");
                return false;
            }
        }

        if($trackingTeam === null) { // this should never happen
            return false;
        } elseif(!$trackingTeam->isAlive()) {
            $session->message("{RED}You can't track an eliminated team!");
            return false;
        }

        $members = $trackingTeam->getMembers();
        $session->setTrackingSession($members[array_rand($members)]);
        return true;
    }

    public function canBePurchased(Session $session): bool {
        return true;
    }

}