<?php

declare(strict_types=1);


namespace sergittos\bedwars\listener;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\item\BedwarsItems;
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
        return BedwarsItems::get($item->getNamedTag()->getString("bedwars_name"));
    }

    private function isCreatingMap(Session $session, Item $item): bool {
        return $session->isCreatingMap() and $item->getNamedTag()->getTag("setup") !== null;
    }

}