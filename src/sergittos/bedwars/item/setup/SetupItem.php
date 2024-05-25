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
use sergittos\bedwars\item\BedwarsItem;

abstract class SetupItem extends BedwarsItem {

    public function asItem(): Item {
        $item = parent::asItem();
        $item->getNamedTag()->setByte("setup", 1);
        return $item;
    }

}