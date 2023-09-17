<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\item\game;


use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\session\Session;

class LeaveGameItem extends BedwarsItem {

    public function __construct() {
        parent::__construct("{RED}Leave game");
    }

    public function onInteract(Session $session): void {
        $session->getGame()->removePlayer($session);
    }

    protected function realItem(): Item {
        return VanillaBlocks::BED()->asItem();
    }

}