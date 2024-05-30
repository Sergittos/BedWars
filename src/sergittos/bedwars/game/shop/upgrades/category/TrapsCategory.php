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
use sergittos\bedwars\game\team\upgrade\trap\Trap;
use sergittos\bedwars\game\team\upgrade\trap\TrapRegistry;
use sergittos\bedwars\session\Session;
use function array_map;

class TrapsCategory extends Category {

    public function __construct() {
        parent::__construct("Traps");
    }

    public function getProducts(Session $session): array {
        $price = $session->getTeam()->getUpgrades()->getTrapsCount() + 1;
        return array_map(function(Trap $trap) use ($price) {
            return new TrapProduct($trap, $price);
        }, TrapRegistry::getAll());
    }

}