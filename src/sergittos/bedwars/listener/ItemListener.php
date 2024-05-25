<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\listener;


use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use sergittos\bedwars\item\BedwarsItemRegistry;
use sergittos\bedwars\session\SessionFactory;
use function strtolower;

class ItemListener implements Listener {

    public function onTransaction(InventoryTransactionEvent $event): void {
        foreach($event->getTransaction()->getActions() as $action) {
            if($action->getSourceItem()->getNamedTag()->getTag("bedwars_item") !== null) {
                $event->cancel();
            }
        }
    }

    public function onUse(PlayerItemUseEvent $event): void {
        $tag = $event->getItem()->getNamedTag()->getTag("bedwars_name");
        if($tag === null) {
            return;
        }

        BedwarsItemRegistry::get(strtolower($tag->getValue()))->onInteract(SessionFactory::getSession($event->getPlayer())); // TODO: Clean
    }

}