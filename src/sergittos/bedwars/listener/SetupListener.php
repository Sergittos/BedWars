<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\listener;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\item\BedwarsItemRegistry;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\SessionFactory;

class SetupListener implements Listener {

    public function onBreak(BlockBreakEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if($this->isCreatingMap($session, $item = $event->getItem())) {
            $event->cancel();

            $session->getMapSetup()->getStep()->onBlockBreak($event->getBlock(), $this->getBedwarsItem($item));
        }
    }

    public function onItemUse(PlayerItemUseEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if($this->isCreatingMap($session, $item = $event->getItem())) {
            $event->cancel();

            $session->getMapSetup()->getStep()->onInteract($this->getBedwarsItem($item));
        }
    }

    public function onInteract(PlayerInteractEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if($this->isCreatingMap($session, $item = $event->getItem())) {
            $event->cancel();

            $session->getMapSetup()->getStep()->onBlockInteract($event->getBlock()->getPosition(), $event->getAction(), $event, $this->getBedwarsItem($item));
        }
    }

    private function getBedwarsItem(Item $item): BedwarsItem {
        return BedwarsItemRegistry::get($item->getNamedTag()->getString("bedwars_name"));
    }

    private function isCreatingMap(Session $session, Item $item): bool {
        return $session->isCreatingMap() and $item->getNamedTag()->getTag("setup") !== null;
    }

}