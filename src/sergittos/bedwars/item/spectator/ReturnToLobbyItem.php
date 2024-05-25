<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\item\spectator;


use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use sergittos\bedwars\session\Session;

class ReturnToLobbyItem extends SpectatorItem {

    public function __construct() {
        parent::__construct("{RED}Return to lobby");
    }

    protected function onSpectatorInteract(Session $session): void {
        $session->getGame()->removeSpectator($session);
    }

    protected function getMaterial(): Item {
        return VanillaBlocks::BED()->setColor(DyeColor::RED())->asItem();
    }

}