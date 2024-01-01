<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\generator\presets;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\generator\GeneratorType;

class DiamondGenerator extends TextGenerator {

    public function getType(): GeneratorType {
        return GeneratorType::DIAMOND;
    }

    public function getInitialSpeed(): int {
        return 45;
    }

   protected function getItem(): Item {
       return VanillaItems::DIAMOND();
   }

}