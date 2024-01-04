<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop;


use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ColorUtils;
use sergittos\bedwars\utils\GameUtils;
use function strtok;
use function strtolower;
use function ucfirst;

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

    public function getDisplayName(Session $session): string {
        $color = TextFormat::RED;
        if($session->getPlayer()->getInventory()->contains($this->ore)) {
            $color = TextFormat::GREEN;
        }
        return $color . $this->name;
    }

    public function getDescription(Session $session): string {
        $name = strtok(strtolower($this->ore->getVanillaName()), " ");
        $color = GameUtils::getGeneratorColor($name);

        if($name !== "iron" and $name !== "gold" and $this->price !== 1) {
            $name .= "s";
        }
        $name = ucfirst($name);

        return ColorUtils::translate("{GRAY}Cost: " . $color . $this->price . " " . ucfirst($name));
    }

    abstract public function canBePurchased(Session $session): bool;

    abstract public function onPurchase(Session $session): bool;

}