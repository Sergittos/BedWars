<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\item\category;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\item\ItemProduct;
use sergittos\bedwars\session\Session;
use function ucfirst;

class ArmorCategory extends Category {

    public function __construct() {
        parent::__construct("Armor");
    }

    /**
     * @return ItemProduct[]
     */
    public function getProducts(Session $session): array {
        return [
            $this->createArmorProduct("chainmail", 30, VanillaItems::IRON_INGOT()),
            $this->createArmorProduct("iron", 12, VanillaItems::GOLD_INGOT()),
            $this->createArmorProduct("diamond", 6, VanillaItems::EMERALD())
        ];
    }

    private function createArmorProduct(string $armor, int $price, Item $ore): ItemProduct {
        return new ItemProduct("Permanent " . ucfirst($armor) . " Armor", $price, 1, VanillaItems::AIR(), $ore, function(Session $session) use ($armor) {
            $settings = $session->getGameSettings();
            if($this->getPriority($settings->getArmor()) < $this->getPriority($armor)) {
                $settings->setArmor($armor);
                return true;
            }
            $session->message("{RED}You already have this armor!");
            return false;
        });
    }

    private function getPriority(?string $armor): int {
        return match($armor) {
            "chainmail" => 1,
            "iron" => 2,
            "diamond" => 3,
            default => 0
        };
    }

}