<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\item\setup;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\shop\Shop;
use sergittos\bedwars\session\Session;

class AddVillagerItem extends SetupItem {

    private Shop $shop;

    public function __construct(Shop $shop) {
        $this->shop = $shop;
        parent::__construct($shop->getName() . " villager");
    }

    public function getShop(): Shop {
        return $this->shop;
    }

    public function onInteract(Session $session): void {}

    protected function getMaterial(): Item {
        return VanillaItems::VILLAGER_SPAWN_EGG();
    }

}