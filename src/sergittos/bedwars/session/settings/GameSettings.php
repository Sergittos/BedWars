<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\settings;


use pocketmine\item\Armor;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\Tool;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\team\upgrade\UpgradeIds;
use sergittos\bedwars\item\BedwarsItemRegistry;
use sergittos\bedwars\session\Session;
use function strtolower;
use function time;

class GameSettings {

    private Session $session;

    private bool $permanentShears = false;

    private ?string $armor = null;

    private int $magicMilkTime = 0;

    private int $pickaxeTier = 0;
    private int $axeTier = 0;

    public function __construct(Session $session) {
        $this->session = $session;
    }

    public function hasPermanentShears(): bool {
        return $this->permanentShears;
    }

    public function hasArmor(): bool {
        return $this->armor !== null;
    }

    public function isUnderMagicMilkEffect(): bool {
        return time() - $this->magicMilkTime < 30;
    }

    public function isPickaxeFullUpgraded(): bool {
        return $this->pickaxeTier >= 4;
    }

    public function isAxeFullUpgraded(): bool {
        return $this->axeTier >= 4;
    }

    public function getArmor(): ?string {
        return $this->armor;
    }

    public function getPickaxeTier(): int {
        return $this->pickaxeTier;
    }

    public function getAxeTier(): int {
        return $this->axeTier;
    }

    public function setPermanentShears(): void {
        $this->permanentShears = true;
    }

    public function setMagicMilk(): void {
        $this->magicMilkTime = time();
    }

    public function incrasePickaxeTier(): void {
        $this->pickaxeTier++;
    }

    public function incraseAxeTier(): void {
        $this->axeTier++;
    }

    public function decreasePickaxeTier(): void {
        if($this->pickaxeTier > 1) {
            $this->pickaxeTier--;
        }
    }

    public function decreaseAxeTier(): void {
        if($this->axeTier > 1) {
            $this->axeTier--;
        }
    }

    public function setArmor(?string $armor): void {
        $this->armor = $armor;
        $this->applyArmor();
    }

    public function apply(): void {
        $player = $this->session->getPlayer();
        if(!$player->isConnected()) {
            return;
        }

        $inventory = $player->getInventory();
        $inventory->addItem(VanillaItems::WOODEN_SWORD()->setUnbreakable());
        $inventory->setItem(8, BedwarsItemRegistry::TRACKER_SHOP());

        if($this->permanentShears) {
            $inventory->addItem(VanillaItems::SHEARS());
        }
        if($this->pickaxeTier > 0) {
            $inventory->addItem($this->getTool("pickaxe", $this->pickaxeTier));
        }
        if($this->axeTier > 0) {
            $inventory->addItem($this->getTool("axe", $this->axeTier));
        }

        $inventory = $player->getArmorInventory();
        $inventory->setHelmet($this->getLeatherArmor(VanillaItems::LEATHER_CAP()));
        $inventory->setChestplate($this->getLeatherArmor(VanillaItems::LEATHER_TUNIC()));

        $this->applyArmor();

        foreach($this->session->getTeam()->getUpgrades()->getAll() as $upgrade) {
            $upgrade->applySession($this->session);
        }
    }

    private function applyArmor(): void {
        $inventory = $this->session->getPlayer()->getArmorInventory();
        if($this->armor !== null) {
            $inventory->setLeggings($this->getArmorPiece("leggings"));
            $inventory->setBoots($this->getArmorPiece("boots"));
        } else {
            $inventory->setLeggings($this->getLeatherArmor(VanillaItems::LEATHER_PANTS()));
            $inventory->setBoots($this->getLeatherArmor(VanillaItems::LEATHER_BOOTS()));
        }
    }

    public function getTool(string $name, int $tier): Tool {
        /** @var Tool $item */
        $item = StringToItemParser::getInstance()->parse(strtolower($this->getMaterial($tier) . "_" . $name));
        $item->setUnbreakable();
        $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), $tier !== 4 ? $tier : 3));
        if($tier === 3) {
            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 3));
        }
        return $item;
    }

    private function getLeatherArmor(Armor $armor): Armor {
        return $this->addComponents($armor->setCustomColor($this->session->getTeam()->getDyeColor()->getRgbValue()))->setUnbreakable();
    }

    private function getArmorPiece(string $piece): Armor {
        return $this->addComponents(StringToItemParser::getInstance()->parse(strtolower($this->armor) . "_" . strtolower($piece)))->setUnbreakable();
    }

    private function addComponents(Item $item): Armor|Item {
        $protectionLevel = $this->session->getTeam()?->getUpgrades()->get(UpgradeIds::ARMOR_PROTECTION)->getLevel();
        if($protectionLevel >= 1) {
            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), $protectionLevel));
        }
        $item->getNamedTag()->setByte("bedwars_item", 1);

        return $item;
    }

    public function getMaterial(int $tier): string {
        return match($tier) {
            0, 1 => "Wooden",
            2 => "Iron",
            3 => "Golden",
            4, 5 => "Diamond"
        };
    }

}