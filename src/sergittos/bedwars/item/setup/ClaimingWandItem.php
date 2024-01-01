<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\setup;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\session\Session;

class ClaimingWandItem extends SetupItem {

    public function __construct() {
        parent::__construct("{AQUA}Claiming wand");
    }

    public function onInteract(Session $session): void {}

    protected function realItem(): Item {
        return VanillaItems::DIAMOND_HOE();
    }

}