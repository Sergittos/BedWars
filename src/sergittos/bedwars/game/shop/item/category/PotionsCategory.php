<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\item\category;


use pocketmine\item\PotionType;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\item\ItemProduct;
use sergittos\bedwars\session\Session;

class PotionsCategory extends Category {

    public function __construct() {
        parent::__construct("Potions");
    }

    /**
     * @return ItemProduct[]
     */
    public function getProducts(Session $session): array {
        return [
            new ItemProduct("Speed II Potion (45 seconds)", 1, 1, VanillaItems::POTION()->setType(PotionType::SWIFTNESS()), VanillaItems::EMERALD()),
            new ItemProduct("Jump V Potion (45 seconds)", 1, 1, VanillaItems::POTION()->setType(PotionType::LEAPING()), VanillaItems::EMERALD()),
            new ItemProduct("Invisibility Potion (30 seconds)", 2, 1, VanillaItems::POTION()->setType(PotionType::INVISIBILITY()), VanillaItems::EMERALD())
        ];
    }

}