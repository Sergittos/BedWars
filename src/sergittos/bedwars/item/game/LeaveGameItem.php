<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\item\game;


use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\session\Session;

class LeaveGameItem extends BedwarsItem {

    public function __construct() {
        parent::__construct("{RED}Leave game");
    }

    public function onInteract(Session $session): void {
        if($session->isPlaying()) {
            $session->getGame()->removePlayer($session);
        }
    }

    protected function getMaterial(): Item {
        return VanillaBlocks::BED()->setColor(DyeColor::RED)->asItem();
    }

}