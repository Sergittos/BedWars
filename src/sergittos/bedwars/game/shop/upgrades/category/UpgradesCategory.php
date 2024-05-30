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
use sergittos\bedwars\game\team\upgrade\Upgrade;
use sergittos\bedwars\session\Session;
use function array_map;

class UpgradesCategory extends Category {

    public function __construct() {
        parent::__construct("Upgrades");
    }

    /**
     * @return UpgradesProduct[]
     */
    public function getProducts(Session $session): array {
        return array_map(function(Upgrade $upgrade) {
            return new UpgradeProduct($upgrade);
        }, $session->getTeam()->getUpgrades()->getAll());
    }

}