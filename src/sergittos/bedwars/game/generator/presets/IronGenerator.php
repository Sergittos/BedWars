<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\generator\presets;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\generator\GeneratorType;

class IronGenerator extends Generator {

    public function getType(): GeneratorType {
        return GeneratorType::IRON;
    }

    public function getInitialSpeed(): int {
        return 1;
    }

    protected function getItem(): Item {
        return VanillaItems::IRON_INGOT();
    }

}