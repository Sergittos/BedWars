<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\settings;


use pocketmine\item\Armor;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\Tool;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\item\BedwarsItems;
use sergittos\bedwars\session\Session;
use function strtolower;
use function time;

class GameSettings {

    private Session $session;

    private bool $permanent_shears = false;

    private ?string $armor = null;

    private int $magic_milk_time = 0;

    private int $pickaxe_tier = 0;
    private int $axe_tier = 0;

    public function __construct(Session $session) {
        $this->session = $session;
    }

    public function hasPermanentShears(): bool {
        return $this->permanent_shears;
    }

    public function hasArmor(): bool {
        return $this->armor !== null;
    }

    public function isUnderMagicMilkEffect(): bool {
        return time() - $this->magic_milk_time < 30;
    }

    public function isPickaxeFullUpgraded(): bool {
        return $this->pickaxe_tier >= 4;
    }

    public function isAxeFullUpgraded(): bool {
        return $this->axe_tier >= 4;
    }

    public function getArmor(): ?string {
        return $this->armor;
    }

    public function getPickaxeTier(): int {
        return $this->pickaxe_tier;
    }

    public function getAxeTier(): int {
        return $this->axe_tier;
    }

    public function setPermanentShears(): void {
        $this->permanent_shears = true;
    }

    public function setMagicMilk(): void {
        $this->magic_milk_time = time();
    }

    public function incrasePickaxeTier(): void {
        $this->pickaxe_tier++;
    }

    public function incraseAxeTier(): void {
        $this->axe_tier++;
    }

    public function decreasePickaxeTier(): void {
        if($this->pickaxe_tier > 1) {
            $this->pickaxe_tier--;
        }
    }

    public function decreaseAxeTier(): void {
        if($this->axe_tier > 1) {
            $this->axe_tier--;
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
        $inventory->setItem(8, BedwarsItems::TRACKER_SHOP()->asItem());

        if($this->permanent_shears) {
            $inventory->addItem(VanillaItems::SHEARS());
        }
        if($this->pickaxe_tier > 0) {
            $inventory->addItem($this->getTool("pickaxe", $this->pickaxe_tier));
        }
        if($this->axe_tier > 0) {
            $inventory->addItem($this->getTool("axe", $this->axe_tier));
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
        $protection_level = $this->session->getTeam()?->getUpgrades()->getArmorProtection()->getLevel();
        if($protection_level >= 1) {
            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), $protection_level));
        }
        $item->getNamedTag()->setByte("bedwars", 1);

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