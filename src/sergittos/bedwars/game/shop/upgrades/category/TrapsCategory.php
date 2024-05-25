<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\upgrades\category;


use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\upgrades\product\TrapProduct;
use sergittos\bedwars\game\shop\upgrades\UpgradesProduct;
use sergittos\bedwars\game\team\Upgrades;
use sergittos\bedwars\session\Session;

class TrapsCategory extends Category {

    public function __construct() {
        parent::__construct("Traps");
    }

    public function getProducts(Session $session): array {
        $upgrades = $session->getTeam()->getUpgrades();
        return [
            $this->createTrapProduct("It's a trap", $upgrades),
            $this->createTrapProduct("Counter-Offensive Trap", $upgrades),
            $this->createTrapProduct("Alarm Trap", $upgrades),
            $this->createTrapProduct("Miner Fatigue Trap", $upgrades)
        ];
    }

    private function createTrapProduct(string $name, Upgrades $upgrades): UpgradesProduct {
        return new TrapProduct($name, $upgrades->getTrapsCount() + 1);
    }

}