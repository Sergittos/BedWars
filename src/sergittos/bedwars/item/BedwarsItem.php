<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\item;


use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ColorUtils;
use function str_replace;

abstract class BedwarsItem {

    private string $name;
    private bool $disableTransactions;

    public function __construct(string $name, bool $disable_transactions = true) {
        $this->name = ColorUtils::translate($name);
        $this->disableTransactions = $disable_transactions;
    }

    public function asItem(): Item {
        $item = $this->getMaterial();
        $item->setCustomName($this->name);

        $nbt = $item->getNamedTag();
        $nbt->setString("bedwars_name", str_replace(" ", "_", TextFormat::clean($this->name)));

        if($this->disableTransactions) {
            $nbt->setByte("bedwars_item", 1);
        }

        return $item;
    }

    abstract public function onInteract(Session $session): void;

    abstract protected function getMaterial(): Item;

}