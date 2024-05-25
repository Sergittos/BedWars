<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop;


use sergittos\bedwars\game\shop\item\ItemShop;
use sergittos\bedwars\game\shop\tracker\TrackerShop;
use sergittos\bedwars\game\shop\upgrades\UpgradesShop;

class ShopFactory {

    /** @var Shop[] */
    static private array $shops = [];

    static public function init(): void { // todo: use enums
        self::addShop(new ItemShop());
        self::addShop(new UpgradesShop());
        self::addShop(new TrackerShop());
    }

    static public function getShop(string $id): ?Shop {
        return self::$shops[$id] ?? null;
    }

    static private function addShop(Shop $shop): void {
        self::$shops[$shop->getId()] = $shop;
    }

}