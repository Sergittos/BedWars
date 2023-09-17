<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\item;


use Closure;
use pocketmine\block\Block;
use pocketmine\item\Item;
use sergittos\bedwars\game\shop\Product;
use sergittos\bedwars\session\Session;

class ItemProduct extends Product {

    private int $amount;

    private Item $item;
    private ?Closure $on_purchase;

    public function __construct(string $name, int $price, int $amount, Block|Item $item, Item $ore, ?Closure $on_purchase = null) {
        $this->amount = $amount;
        $this->item = ($item instanceof Block ? $item->asItem() : $item)->setCount($amount);
        $this->on_purchase = $on_purchase;
        parent::__construct($name, $name, $price, $ore);
    }

    public function getAmount(): int {
        return $this->amount;
    }

    public function getItem(): Item {
        return clone $this->item;
    }

    public function onPurchase(Session $session): bool {
        $inventory = $session->getPlayer()->getInventory();
        if(!$inventory->canAddItem($this->item)) {
            $session->message("{RED}Your inventory is full!");
            return false;
        }

        if($this->executePurchaseListener($session)) {
            $inventory->addItem($this->item);
            return true;
        }
        return false;
    }

    private function executePurchaseListener(Session $session): bool {
        return $this->on_purchase !== null ? ($this->on_purchase)($session) : true;
    }

}