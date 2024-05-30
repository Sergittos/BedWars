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
use sergittos\bedwars\game\shop\Shop;

class TrackerShop extends Shop {

    public function getName(): string {
        return "Tracker";
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array {
        return [
            new TrackerCategory()
        ];
    }

}