<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

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

    protected function getMaterial(): Item {
        return VanillaBlocks::IRON()->asItem();
    }

}