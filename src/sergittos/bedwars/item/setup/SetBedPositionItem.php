<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\setup;


use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use sergittos\bedwars\session\Session;

class SetBedPositionItem extends SetupItem {

    private ?DyeColor $color = null;

    public function __construct() {
        parent::__construct("Set bed position");
    }

    public function setColor(DyeColor $color): void {
        $this->color = $color;
    }

    public function onInteract(Session $session): void {}

    protected function realItem(): Item {
        $block = VanillaBlocks::BED();
        if($this->color !== null) {
            $block->setColor($this->color);
        }
        return $block->asItem();
    }

}