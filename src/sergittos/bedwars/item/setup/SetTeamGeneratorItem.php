<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\setup;


use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use sergittos\bedwars\session\Session;

class SetTeamGeneratorItem extends SetupItem {

    public function __construct() {
        parent::__construct("Team generator");
    }

    public function onInteract(Session $session): void {}

    protected function realItem(): Item {
        return VanillaBlocks::IRON()->asItem();
    }

}