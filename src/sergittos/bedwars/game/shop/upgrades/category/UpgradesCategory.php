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
use sergittos\bedwars\game\shop\upgrades\product\UpgradeProduct;
use sergittos\bedwars\game\shop\upgrades\UpgradesProduct;
use sergittos\bedwars\session\Session;

class UpgradesCategory extends Category {

    public function __construct() {
        parent::__construct("Upgrades");
    }

    /**
     * @return UpgradesProduct[]
     */
    public function getProducts(Session $session): array {
        $upgrades = $session->getTeam()->getUpgrades();
        return [
            new UpgradeProduct("Sharpened Swords", 4),
            new UpgradeProduct("Armor Protection", 2 ** ($upgrades->getArmorProtection()->getLevel() + 1)),
            new UpgradeProduct("Maniac Miner", 2 ** ($upgrades->getManiacMiner()->getLevel() + 1)),
            new UpgradeProduct("Iron Forge", 2 * ($upgrades->getIronForge()->getLevel() + 1)),
            new UpgradeProduct("Heal Pool", 1)
        ];
    }

}