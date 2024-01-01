<?php

declare(strict_types=1);


namespace sergittos\bedwars\listener;


use pocketmine\block\Air;
use pocketmine\block\Bed;
use pocketmine\block\Chest;
use pocketmine\block\Glass;
use pocketmine\block\TNT;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\object\ItemEntity;
use pocketmine\entity\projectile\Arrow;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityEffectAddEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\entity\EntityPreExplodeEvent;
use pocketmine\event\entity\ItemMergeEvent;
use pocketmine\event\entity\ItemSpawnEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBedEnterEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\MilkBucket;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\EntityEventBroadcaster;
use pocketmine\network\mcpe\NetworkBroadcastUtils;
use pocketmine\network\mcpe\protocol\MoveActorAbsolutePacket;
use pocketmine\player\chat\LegacyRawChatFormatter;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\game\entity\shop\Villager;
use sergittos\bedwars\game\Game;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\SessionFactory;
use sergittos\bedwars\utils\GameUtils;
use sergittos\bedwars\utils\MathUtils;
use function array_map;
use function in_array;
use function strtoupper;

class GameListener implements Listener {

    public function onReceiveDamage(EntityDamageEvent $event): void {
        $entity = $event->getEntity();
        if(!$entity instanceof Player) {
            return;
        }

        $session = SessionFactory::getSession($entity);
        if(!$session->isPlaying()) {
            return;
        }

        $effects = $entity->getEffects();
        if($effects->has($effect = VanillaEffects::INVISIBILITY())) {
            $effects->remove($effect);
            $session->message("{RED}You took damage and lost your invisibility!");
        }

        if($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_EXPLOSION) {
            $event->setBaseDamage(0);
        }

        if($event->getFinalDamage() >= $entity->getHealth()) {
            $session->kill($event->getCause());
            $event->cancel();
        }
    }

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event): void {
        $damager = $event->getDamager();
        $entity = $event->getEntity();
        if(!$damager instanceof Player or !$entity instanceof Player) {
            return;
        }
        $damager_session = SessionFactory::getSession($damager);
        $entity_session = SessionFactory::getSession($entity);

        $entity_session->setLastSessionHit($damager_session);

        if($damager_session->isPlaying() and $entity_session->isPlaying() and
            $damager_session->hasTeam() and $entity_session->hasTeam() and
            $damager_session->getTeam()->hasMember($entity_session)) {
            $event->cancel();
        }
    }

    public function onInteract(PlayerInteractEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if(!$session->isPlaying()) {
            return;
        }
        $block = $event->getBlock();
        if(!$block instanceof Chest) {
            return;
        }

        $team = $this->getTeamByPosition($session->getGame(), $block->getPosition());
        if($team !== null and $team->isAlive() and $team->getName() !== $session->getTeam()->getName()) {
            $session->message("{RED}You can't open this chest!");
            $event->cancel();
        }
    }

    public function onBreak(BlockBreakEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if(!$session->isPlaying()) {
            return;
        }

        $block = $event->getBlock();
        $position = $block->getPosition();
        $game = $session->getGame();

        if($game->checkBlock($position)) {
            return;
        } elseif(!$block instanceof Bed) {
            $session->message("{RED}You can only break blocks placed by a player!");
            $event->cancel();
            return;
        }

        $team = $this->getTeamByPosition($game, $position);
        $bed_position = $team->getBedPosition();
        $half_position = $block->getOtherHalf()->getPosition();

        if($team->isBedDestroyed() or $half_position === null or (!$bed_position->equals($half_position) and !$bed_position->equals($position))) {
            return;
        }

        if($session->getTeam()->getName() !== $team->getName()) {
            $team->destroyBed($game, false);
            $game->broadcastMessage("{BOLD}{WHITE}BED DESTRUCTION > {RESET}" . $team->getColoredName() . " Bed {GRAY}was destroyed by " . $session->getColoredUsername() . "{GRAY}!");
            $event->setDrops([]);
        } else {
            $session->message("{RED}You can't destroy your own bed!");
            $event->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if(!$session->isPlaying()) {
            return;
        }

        if($session->getTeam()->getClaim()->isInside($event->getBlockAgainst()->getPosition())) {
            $session->message("{RED}You can't place blocks here!");
            $event->cancel();
            return;
        }

        foreach($event->getTransaction()->getBlocks() as [$x, $y, $z, $block]) {
            if($block instanceof TNT) {
                BedWars::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($block) {
                    $block->ignite(60);
                }), 1);
                continue;
            }
            $session->getGame()->addBlock($block->getPosition());
        }
    }

    public function onMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();
        $session = SessionFactory::getSession($player);
        if(!$session->isPlaying()) {
            return;
        }

        $this->checkEntities($player);

        if($session->getGameSettings()->isUnderMagicMilkEffect()) {
            return;
        }

        $team = $this->getTeamByPosition($session->getGame(), $player->getPosition());
        if($team === null or $team->isBedDestroyed() or $session->getTeam()->getName() === $team->getName()) {
            return;
        }

        $upgrades = $team->getUpgrades();
        if($upgrades->canTriggerTrap()) {
            $upgrades->triggerPrimaryTrap($session, $team);
        }
    }

    public function onChat(PlayerChatEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if(!$session->hasGame()) {
            return;
        }

        $team = $session->getTeam();
        if($session->isPlaying() and $team !== null) {
            $prefix = $team->getColor() . "[" . strtoupper($team->getName()) . "] ";
        } elseif($session->isSpectator()) {
            $prefix = TextFormat::DARK_GRAY . "[SPECTATOR] ";
        } else {
            $prefix = "";
        }

        $event->setFormatter(new LegacyRawChatFormatter(
            $prefix . TextFormat::GRAY . "{%0}: {%1}"
        ));
        $event->setRecipients(array_map(function(Session $session) {
            return $session->getPlayer();
        }, $session->getGame()->getPlayersAndSpectators()));
    }

    public function onEffectAdd(EntityEffectAddEvent $event): void {
        $entity = $event->getEntity();
        if(!$entity instanceof Player or !SessionFactory::getSession($entity)?->isPlaying()) {
            return;
        }

        $effect = $event->getEffect();
        $duration = GameUtils::getEffectDuration($effect);
        if($duration !== 0) {
            $effect->setDuration($duration);
            $effect->setAmplifier(GameUtils::getEffectAmplifier($effect));
            $effect->setVisible(false);
        }
    }

    public function onEntityDamageByChildEntity(EntityDamageByChildEntityEvent $event): void {
        $damager = $event->getDamager();
        $entity = $event->getEntity();
        if((!$damager instanceof Player or !SessionFactory::getSession($damager)?->isPlaying()) or
            (!$entity instanceof Player or !SessionFactory::getSession($entity)?->isPlaying())) {
            return;
        }

        $child = $event->getChild();
        if($child instanceof Arrow) {
            SessionFactory::getSession($damager)->playSound("random.orb");
        }
    }

    public function onExplode(EntityExplodeEvent $event): void {
        $game = BedWars::getInstance()->getGameManager()->getGameByWorld($event->getPosition()->getWorld());
        if($game === null) {
            return;
        }

        $block_list = [];
        foreach($event->getBlockList() as $block) {
            if(!$game->checkBlock($block->getPosition()) or $block instanceof Glass) {
                continue;
            }
            $block_list[] = $block;
        }

        $event->setBlockList($block_list);
    }

    public function onCraft(CraftItemEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if($session->isPlaying()) {
            $event->cancel();
        }
    }

    public function onConsume(PlayerItemConsumeEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        if($session->isPlaying() and $event->getItem() instanceof MilkBucket) {
            $session->getGameSettings()->setMagicMilk();
        }
    }

    public function onDrop(PlayerDropItemEvent $event): void {
        $session = SessionFactory::getSession($player = $event->getPlayer());
        if($session->isPlaying() and
            in_array($event->getItem()->getTypeId(), [ItemTypeIds::IRON_INGOT, ItemTypeIds::GOLD_INGOT, ItemTypeIds::DIAMOND, ItemTypeIds::EMERALD]) and
            $player->getWorld()->getBlock($player->getPosition()->subtract(0, 2, 0)) instanceof Air) {
            $event->cancel();
        }
    }

    public function onEntityEvent(ItemSpawnEvent $event): void {
        $entity = $event->getEntity();
        if($entity->getOwner() === "generator") {
            $entity->setPickupDelay(0);
        }
    }

    public function onPickup(EntityItemPickupEvent $event): void {
        /** @var ItemEntity $item_entity */
        $item_entity = $event->getOrigin();
        if($item_entity->getOwner() !== "generator") {
            return;
        }

        $event->cancel();

        $world = $item_entity->getWorld();
        foreach($world->getNearbyEntities($item_entity->getBoundingBox()->expandedCopy(1, 0.5, 1), $item_entity) as $entity) {
            if(!$entity instanceof Player) {
                continue;
            }

            NetworkBroadcastUtils::broadcastEntityEvent(
                $item_entity->getViewers(),
                fn(EntityEventBroadcaster $broadcaster, array $recipients) => $broadcaster->onPickUpItem($recipients, $entity, $item_entity)
            );

            foreach($entity->getInventory()->addItem($event->getItem()) as $remains) {
                $world->dropItem($item_entity->getLocation(), $remains, new Vector3(0, 0, 0));
            }
        }

        $item_entity->flagForDespawn();
    }

    public function onMerge(ItemMergeEvent $event): void {
        $item = $event->getTarget()->getItem();
        if($item->getCount() >= GameUtils::getCountById($item->getTypeId())) {
            $event->getEntity()->flagForDespawn();
            $event->cancel();
        }
    }

    public function onQuit(PlayerQuitEvent $event): void {
        $session = SessionFactory::getSession($event->getPlayer());
        $game = $session->getGame();

        if($session->isPlaying()) {
            $game->removePlayer($session, false);
        } elseif($session->isSpectator()) {
            $game->removeSpectator($session);
        }
    }

    public function onExplosionPrime(EntityPreExplodeEvent $event): void {
        $event->setRadius(5);
    }

    public function onExhaust(PlayerExhaustEvent $event): void {
        $event->cancel();
    }

    public function onBedEnter(PlayerBedEnterEvent $event): void {
        $event->cancel();
    }

    /**
     * @handleCancelled
     */
    public function onItemUse(PlayerItemUseEvent $event): void {
        if(SessionFactory::getSession($event->getPlayer())->isSpectator()) {
            $event->uncancel();
        }
    }

    private function checkEntities(Player $player): void {
        $network_session = $player->getNetworkSession();
        $position = $player->getPosition();
        foreach($player->getWorld()->getNearbyEntities($player->getBoundingBox()->expandedCopy(12, 12, 12), $player) as $entity) {
            if(!$entity instanceof Villager) {
                continue;
            }

            $yaw = MathUtils::calculateYaw($position, $location = $entity->getLocation());
            $pitch = MathUtils::calculatePitch($position, $location);

            $network_session->sendDataPacket(MoveActorAbsolutePacket::create(
                $entity->getId(),
                $location,
                $pitch,
                $yaw,
                $yaw,
                0
            ));
        }
    }

    private function getTeamByPosition(Game $game, Vector3 $position): ?Team {
        foreach($game->getTeams() as $team) {
            if($team->getZone()->isInside($position)) {
                return $team;
            }
        }
        return null;
    }

}