<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\item\category;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\item\ItemProduct;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\settings\GameSettings;
use function ucfirst;

class ArmorCategory extends Category {

    public function __construct() {
        parent::__construct("Armor");
    }

    /**
     * @return ItemProduct[]
     */
    public function getProducts(Session $session): array {
        $settings = $session->getGameSettings();
        return [
            $this->createArmorProduct("chainmail", 30, VanillaItems::IRON_INGOT(), $settings),
            $this->createArmorProduct("iron", 12, VanillaItems::GOLD_INGOT(), $settings),
            $this->createArmorProduct("diamond", 6, VanillaItems::EMERALD(), $settings)
        ];
    }

    private function createArmorProduct(string $armor, int $price, Item $ore, GameSettings $settings): ItemProduct {
        return new ItemProduct("Permanent " . ucfirst($armor) . " Armor", $price, 0, VanillaItems::AIR(), $ore, function(Session $session) use ($armor) {
            $session->getGameSettings()->setArmor($armor);
        }, $this->getPriority($settings->getArmor()) < $this->getPriority($armor));
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