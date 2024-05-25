<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\item\spectator;


use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use sergittos\bedwars\form\spectator\SpectatorSettingsForm;
use sergittos\bedwars\session\Session;

class SpectatorSettingsItem extends SpectatorItem {

    public function __construct() {
        parent::__construct("{YELLOW}Spectator settings");
    }

    protected function onSpectatorInteract(Session $session): void {
        $session->getPlayer()->sendForm(new SpectatorSettingsForm($session));
    }

    protected function getMaterial(): Item {
        return VanillaBlocks::REDSTONE_COMPARATOR()->asItem();
    }

}