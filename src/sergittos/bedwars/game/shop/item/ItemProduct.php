<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\item;


use Closure;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\shop\Product;
use sergittos\bedwars\session\Session;

class ItemProduct extends Product {

    private int $amount;

    private Item $item;
    private ?Closure $onPurchase;

    private bool $canBePurchased;

    public function __construct(string $name, int $price, int $amount, Block|Item $item, Item $ore, ?Closure $onPurchase = null, bool $canBePurchased = true) {
        $this->amount = $amount;
        $this->item = ($item instanceof Block ? $item->asItem() : $item)->setCount($amount);
        $this->onPurchase = $onPurchase;
        $this->canBePurchased = $canBePurchased;
        parent::__construct($name, $name, $price, $ore);
    }

    public function getItem(): Item {
        return clone $this->item;
    }

    public function canBePurchased(Session $session): bool {
        return $this->canBePurchased;
    }

    public function getDisplayName(Session $session): string {
        $name = parent::getDisplayName($session);
        if(!$this->canBePurchased) {
            $name = TextFormat::clean($name);
            $name = TextFormat::RED . $name;
        }
        if($this->amount > 1) {
            $name .= " x" . $this->amount;
        }
        return $name;
    }

    public function getDescription(Session $session): string {
        $description = parent::getDescription($session);
        if(!$this->canBePurchased) {
            $description = TextFormat::RED . "You already have this product!";
        }
        return $description;
    }

    public function onPurchase(Session $session): bool {
        $inventory = $session->getPlayer()->getInventory();
        if(!$inventory->canAddItem($this->item)) {
            $session->message("{RED}Your inventory is full!");
            return false;
        }

        $this->executePurchaseListener($session);
        $inventory->addItem($this->item);
        return true;
    }

    private function executePurchaseListener(Session $session): void {
        if($this->onPurchase !== null) {
            ($this->onPurchase)($session);
        }
    }

}