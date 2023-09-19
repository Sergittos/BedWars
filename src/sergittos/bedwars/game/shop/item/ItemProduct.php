<?php

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
    private ?Closure $on_purchase;

    private bool $can_be_purchased;

    public function __construct(string $name, int $price, int $amount, Block|Item $item, Item $ore, ?Closure $on_purchase = null, bool $can_be_purchased = true) {
        $this->amount = $amount;
        $this->item = ($item instanceof Block ? $item->asItem() : $item)->setCount($amount);
        $this->on_purchase = $on_purchase;
        $this->can_be_purchased = $can_be_purchased;
        parent::__construct($name, $name, $price, $ore);
    }

    public function getAmount(): int {
        return $this->amount;
    }

    public function getItem(): Item {
        return clone $this->item;
    }

    public function canBePurchased(): bool {
        return $this->can_be_purchased;
    }

    public function getDisplayName(Session $session): string {
        $name = parent::getDisplayName($session);
        if(!$this->can_be_purchased) {
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
        if(!$this->can_be_purchased) {
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