<?php

declare(strict_types=1);


namespace sergittos\bedwars\listener;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Cancellable;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;

class SpawnProtectionListener implements Listener {

    public function onBlockPlace(BlockPlaceEvent $event): void {
        $this->checkPlayerEvent($event);
    }

    public function onBlockBreak(BlockBreakEvent $event): void {
        $this->checkPlayerEvent($event);
    }

    public function onInteract(PlayerInteractEvent $event): void {
        $this->checkPlayerEvent($event);
    }

    public function onDrop(PlayerDropItemEvent $event): void {
        $this->checkPlayerEvent($event);
    }

    public function onDamage(EntityDamageEvent $event): void {
        $entity = $event->getEntity();
        if($entity instanceof Player) {
            $this->checkWorldProtection($entity, $event);
        }
    }

    private function checkPlayerEvent(Cancellable $cancellable): void {
        $this->checkWorldProtection($cancellable->getPlayer(), $cancellable);
    }

    private function checkWorldProtection(Player $player, Cancellable $cancellable): void {
        if($player->getGamemode() !== GameMode::CREATIVE and $player->getWorld() === Server::getInstance()->getWorldManager()->getDefaultWorld()) {
            $cancellable->cancel();
        }
    }

}