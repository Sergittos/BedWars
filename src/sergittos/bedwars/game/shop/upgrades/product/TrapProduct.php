<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\upgrades\product;


use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\shop\upgrades\UpgradesProduct;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\GameUtils;

class TrapProduct extends UpgradesProduct {

    public function canBePurchased(Session $session): bool {
        $upgrades = $session->getTeam()->getUpgrades();
        return !$upgrades->hasTrap($this->name) and $upgrades->getTrapsCount() < 3;
    }

    protected function purchase(Team $team): void {
        $team->getUpgrades()->addTrap(GameUtils::getTrapByName($this->name));
    }

    public function getDescription(Session $session): string {
        $upgrades = $session->getTeam()->getUpgrades();
        if($upgrades->hasTrap($this->name)) {
            return TextFormat::RED . "You already have this trap!";
        }
        if($upgrades->getTrapsCount() >= 3) {
            return TextFormat::RED . "You cannot have more than 3 traps!";
        }
        return parent::getDescription($session);
    }

}