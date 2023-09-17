<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\game;


use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\ProjectileItem;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\entity\misc\Fireball as FireballEntity;

class Fireball extends ProjectileItem {

    public function __construct() {
        parent::__construct(new ItemIdentifier(ItemTypeIds::FIRE_CHARGE), "Fireball");

        $this->setCustomName(TextFormat::RED . "Fireball");
    }

    protected function createEntity(Location $location, Player $thrower): Throwable {
        return new FireballEntity($location, $thrower);
    }

    public function getThrowForce(): float {
        return 1.5;
    }

}