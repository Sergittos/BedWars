<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\setup;


use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use sergittos\bedwars\session\Session;

class ExitSetupItem extends SetupItem {

    public function __construct() {
        parent::__construct("Exit setup");
    }

    public function onInteract(Session $session): void {
        $session->setMapSetup(null);
        $session->teleportToHub();
    }

    protected function realItem(): Item {
        return VanillaBlocks::BED()->setColor(DyeColor::RED)->asItem();
    }

}