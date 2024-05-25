<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\item\category;


use Closure;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\shop\Category;
use sergittos\bedwars\game\shop\item\ItemProduct;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\settings\GameSettings;
use sergittos\bedwars\utils\GameUtils;

class ToolsCategory extends Category {

    public function __construct() {
        parent::__construct("Tools");
    }

    /**
     * @return ItemProduct[]
     */
    public function getProducts(Session $session): array {
        $settings = $session->getGameSettings();
        return [
            new ItemProduct("Permanent Shears", 20, 1, VanillaItems::SHEARS(), VanillaItems::IRON_INGOT(), function(Session $session) {
                $session->getGameSettings()->setPermanentShears();
                return true;
            }),
            $this->createToolProduct("Pickaxe", $settings->getPickaxeTier(), $settings, $settings->isPickaxeFullUpgraded(), function() use ($settings) {
                $settings->incrasePickaxeTier();
            }),
            $this->createToolProduct("Axe", $settings->getAxeTier(), $settings, $settings->isAxeFullUpgraded(), function() use ($settings) {
                $settings->incraseAxeTier();
            })
        ];
    }

    private function createToolProduct(string $name, int $tier, GameSettings $settings, bool $isFullUpgraded, Closure $onPurchase): ItemProduct {
        $tier++;
        return new ItemProduct(
            $settings->getMaterial($tier) . " $name " . ($tier <= 4 ? "Tier " . GameUtils::intToRoman($tier) : "MAX TIER"),
            $this->getPrice($tier), 1, $settings->getTool($name, $tier), $this->getOre($tier), function(Session $session) use ($name, $tier, $settings, $isFullUpgraded, $onPurchase) {
                $session->getPlayer()->getInventory()->remove($settings->getTool($name, $tier - 1));
                $onPurchase();
        }, !$isFullUpgraded);
    }

    private function getPrice(int $tier): int {
        return match($tier) {
            1, 2 => 10,
            3 => 3,
            4 => 6,
            5 => 0
        };
    }

    private function getOre(int $tier): Item {
        return match($tier) {
            1, 2 => VanillaItems::IRON_INGOT(),
            3, 4 => VanillaItems::GOLD_INGOT(),
            5 => VanillaItems::AIR()
        };
    }

}