<?php

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