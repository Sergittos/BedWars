<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

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

    protected function getMaterial(): Item {
        return VanillaBlocks::BED()->setColor(DyeColor::RED)->asItem();
    }

}