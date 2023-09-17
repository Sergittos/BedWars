<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\entity\misc;


use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\projectile\Throwable;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\Explosion;
use pocketmine\world\Position;

class Fireball extends Throwable {

    public static function getNetworkTypeId(): string {
        return EntityIds::FIREBALL;
    }

    protected function getInitialSizeInfo(): EntitySizeInfo {
        return new EntitySizeInfo(0.31, 0.31);
    }

    protected function getInitialGravity(): float {
        return 0.01;
    }

    protected function onHit(ProjectileHitEvent $event): void {
        $explosion = new Explosion(Position::fromObject($event->getRayTraceResult()->getHitVector(), $this->getWorld()), 4, $this);
        $explosion->explodeA();
        $explosion->explodeB();
    }

}