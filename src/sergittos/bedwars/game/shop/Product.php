<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop;


use pocketmine\item\Item;
use sergittos\bedwars\session\Session;

abstract class Product {

    protected string $id;
    protected string $name;

    protected int $price;

    protected Item $ore;

    public function __construct(string $id, string $name, int $price, Item $ore) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->ore = $ore->setCount($price);
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): int {
        return $this->price;
    }

    public function getOre(): Item {
        return clone $this->ore;
    }

    abstract public function onPurchase(Session $session): bool;

}