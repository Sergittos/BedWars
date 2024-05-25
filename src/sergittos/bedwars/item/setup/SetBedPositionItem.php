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

class SetBedPositionItem extends SetupItem {

    private ?DyeColor $color = null;

    public function __construct() {
        parent::__construct("Bed position");
    }

    public function setColor(DyeColor $color): self {
        $this->color = $color;
        return $this;
    }

    public function onInteract(Session $session): void {}

    protected function getMaterial(): Item {
        $block = VanillaBlocks::BED();
        if($this->color !== null) {
            $block->setColor($this->color);
        }
        return $block->asItem();
    }

}