<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\presets;


use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use sergittos\bedwars\game\team\upgrade\Upgrade;
use sergittos\bedwars\session\Session;

class ArmorProtection extends Upgrade {

    public function getName(): string {
        return "Armor Protection";
    }

    public function getLevels(): int {
        return 4;
    }

    public function internalApplySession(Session $session): void {
        $inventory = $session->getPlayer()->getArmorInventory();
        foreach($inventory->getContents() as $index => $item) {
            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), $this->level));
            $inventory->setItem($index, $item);
        }
    }

}