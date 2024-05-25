<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\upgrades;


use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\Shop;
use sergittos\bedwars\game\shop\upgrades\category\TrapsCategory;
use sergittos\bedwars\game\shop\upgrades\category\UpgradesCategory;

class UpgradesShop extends Shop {

    public function getId(): string {
        return Shop::UPGRADES;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array {
        return [
            new UpgradesCategory(),
            new TrapsCategory()
        ];
    }

}