<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\step;


use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\shop\Shop;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\item\BedwarsItemRegistry;
use sergittos\bedwars\item\setup\AddVillagerItem;

class SetShopAndUpgradesStep extends Step {

    protected function onStart(): void {
        $this->session->clearAllInventories();
        $this->session->message("{YELLOW}Use the eggs you received in your inventory to set the shop and upgrades of the team");

        $inventory = $this->session->getPlayer()->getInventory();
        $inventory->setItem(0, BedwarsItemRegistry::ITEM_VILLAGER());
        $inventory->setItem(1, BedwarsItemRegistry::UPGRADES_VILLAGER());
        $inventory->setItem(8, BedwarsItemRegistry::CANCEL()->setCustomName(TextFormat::GREEN . "Done"));
    }

    public function onBlockInteract(Vector3 $touchVector, int $action, Cancellable $event, BedwarsItem $item): void {
        if($action !== PlayerInteractEvent::RIGHT_CLICK_BLOCK or !$item instanceof AddVillagerItem) {
            return;
        }

        $map = $this->session->getMapSetup()->getMapBuilder();

        $name = $item->getName();
        match($name) {
            Shop::ITEM => $map->addShopPosition($touchVector),
            Shop::UPGRADES => $map->addUpgradesPosition($touchVector)
        };

        $this->session->message("{GREEN}You have successfully set the $name position.");
    }

}