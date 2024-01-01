<?php

declare(strict_types=1);


namespace sergittos\bedwars\listener;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
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
            $session->getMapSetup()->getStep()->onBlockBreak($event->getBlock(), $this->getBedwarsItem($item));

            $event->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $event): void {
        $item = $event->getItem();

        $session = SessionFactory::getSession($event->getPlayer());
        if(!$this->isCreatingMap($session, $item)) {
            return;
        }

        $event->cancel();

        $item = $this->getBedwarsItem($item);
        foreach($event->getTransaction()->getBlocks() as [$x, $y, $z, $block]) {
            $session->getMapSetup()->getStep()->onBlockPlace($block, $item);
        }
    }

    public function onItemUse(PlayerItemUseEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if($this->isCreatingMap($session, $item = $event->getItem())) {
            $session->getMapSetup()->getStep()->onInteract($this->getBedwarsItem($item));

            $event->cancel();
        }
    }

    public function onInteract(PlayerInteractEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if($this->isCreatingMap($session, $item = $event->getItem())) {
            $session->getMapSetup()->getStep()->onBlockInteract($event->getTouchVector(), $event->getAction(), $this->getBedwarsItem($item));

            $event->cancel();
        }
    }

    private function isSetupItem(Item $item): bool {
        return $item->getNamedTag()->getTag("setup") !== null;
    }

    private function getBedwarsItem(Item $item): BedwarsItem {
        return BedwarsItems::get($item->getNamedTag()->getString("bedwars_item"));
    }

    private function isCreatingMap(Session $session, Item $item): bool {
        return $session->isCreatingMap() and $this->isSetupItem($item);
    }

}