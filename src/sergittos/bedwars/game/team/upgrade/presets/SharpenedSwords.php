<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\presets;


use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Sword;
use sergittos\bedwars\game\team\upgrade\Upgrade;
use sergittos\bedwars\session\Session;

class SharpenedSwords extends Upgrade {

    public function getName(): string {
        return "Sharpened Swords";
    }

    public function getLevels(): int {
        return 1;
    }

    public function internalApplySession(Session $session): void {
        $inventory = $session->getPlayer()->getInventory();
        foreach($inventory->getContents() as $index => $item) {
            if($item instanceof Sword) {
                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS()));
                $inventory->setItem($index, $item);
            }
        }
    }

}