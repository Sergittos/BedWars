<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\item\spectator;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\form\spectator\TeleporterForm;
use sergittos\bedwars\session\Session;

class TeleporterItem extends SpectatorItem {

    public function __construct() {
        parent::__construct("{GREEN}Teleporter");
    }

    protected function onSpectatorInteract(Session $session): void {
        $session->getPlayer()->sendForm(new TeleporterForm($session));
    }

    protected function getMaterial(): Item {
        return VanillaItems::COMPASS();
    }

}