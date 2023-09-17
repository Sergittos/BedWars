<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\item\category;


use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\item\ItemProduct;
use sergittos\bedwars\session\Session;

class ToolsCategory extends Category {

    public function __construct() {
        parent::__construct("Tools");
    }

    /**
     * @return ItemProduct[]
     */
    public function getProducts(Session $session): array {
        $efficiency = new EnchantmentInstance(VanillaEnchantments::EFFICIENCY());
        return [
            new ItemProduct("Permanent Shears", 20, 1, VanillaItems::SHEARS(), VanillaItems::IRON_INGOT(), function(Session $session) {
                $session->getGameSettings()->setPermanentShears();
                return true;
            }),
            new ItemProduct("Wooden Pickaxe (Efficiency I)", 10, 1, VanillaItems::WOODEN_PICKAXE()->addEnchantment($efficiency), VanillaItems::IRON_INGOT()),
            new ItemProduct("Wooden Axe (Efficiency I)", 10, 1, VanillaItems::WOODEN_AXE()->addEnchantment($efficiency), VanillaItems::IRON_INGOT())
        ];
    }

}