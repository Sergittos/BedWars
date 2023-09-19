<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\item\category;


use pocketmine\item\Durable;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\item\ItemProduct;
use sergittos\bedwars\session\Session;
use function ucfirst;

class MeleeCategory extends Category {

    public function __construct() {
        parent::__construct("Melee");
    }

    /**
     * @return ItemProduct[]
     */
    public function getProducts(Session $session): array {
        return [
            $this->createMeleeProduct("stone", 10, VanillaItems::IRON_INGOT(), $session),
            $this->createMeleeProduct("iron", 7, VanillaItems::GOLD_INGOT(), $session),
            $this->createMeleeProduct("diamond", 4, VanillaItems::EMERALD(), $session),
            new ItemProduct(
                "Stick (Knockback I)", 5, 1,
                VanillaItems::STICK()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::KNOCKBACK())),
                VanillaItems::GOLD_INGOT()
            )
        ];
    }

    private function createMeleeProduct(string $name, int $price, Item $ore, Session $session): ItemProduct {
        $sword = StringToItemParser::getInstance()->parse($name . "_sword");
        if($sword instanceof Durable) {
            $sword->setUnbreakable();
        }
        if(!$session->getTeam()->getUpgrades()->getSharpenedSwords()->canLevelUp()) {
            $sword->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS()));
        }

        return new ItemProduct(ucfirst($name) . " Sword", $price, 1, $sword, $ore, function(Session $session) {
            $session->getPlayer()->getInventory()->remove(VanillaItems::WOODEN_SWORD());
        });
    }

}