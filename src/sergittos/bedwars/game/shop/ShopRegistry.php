<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop;


use pocketmine\utils\RegistryTrait;
use sergittos\bedwars\game\shop\item\ItemShop;
use sergittos\bedwars\game\shop\tracker\TrackerShop;
use sergittos\bedwars\game\shop\upgrades\UpgradesShop;

/**
 * @method static ItemShop ITEM()
 * @method static TrackerShop TRACKER()
 * @method static UpgradesShop UPGRADES()
 */
class ShopRegistry {
    use RegistryTrait;

    static protected function setup(): void {
        self::register("item", new ItemShop());
        self::register("tracker", new TrackerShop());
        self::register("upgrades", new UpgradesShop());
    }

    static private function register(string $name, Shop $shop): void {
        self::_registryRegister($name, $shop);
    }

}