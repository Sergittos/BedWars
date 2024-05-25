<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\item\setup;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\session\Session;

class CreateMapItem extends BedwarsItem {

    public function __construct() {
        parent::__construct("Create map");
    }

    public function onInteract(Session $session): void {
        $setup = $session->getMapSetup();
        if($setup->getMapBuilder()->canBeBuilt()) {
            $session->teleportToHub();
            $session->message("{GREEN}Map created successfully.");

            $setup->createMap();
        } else {
            $session->message("{RED}You must set all the map properties before creating it.");
        }
    }

    protected function getMaterial(): Item {
        return VanillaItems::EMERALD();
    }

}