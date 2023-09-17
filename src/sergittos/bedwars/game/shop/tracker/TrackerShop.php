<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\tracker;


use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\Shop;

class TrackerShop extends Shop {

    public function getId(): string {
        return Shop::TRACKER;
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