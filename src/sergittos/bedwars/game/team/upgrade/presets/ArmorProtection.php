<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\presets;


use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use sergittos\bedwars\game\team\upgrade\Upgrade;
use sergittos\bedwars\game\team\upgrade\UpgradeIds;
use sergittos\bedwars\session\Session;

class ArmorProtection extends Upgrade {

    public function getId(): string {
        return UpgradeIds::ARMOR_PROTECTION;
    }

    public function getName(): string {
        return "Armor Protection";
    }

    public function getLevels(): int {
        return 4;
    }

    public function getPrice(): int {
        return 2 ** ($this->level + 1);
    }

    public function internalApplySession(Session $session): void {
        $inventory = $session->getPlayer()->getArmorInventory();
        foreach($inventory->getContents() as $index => $item) {
            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), $this->level));
            $inventory->setItem($index, $item);
        }
    }

}