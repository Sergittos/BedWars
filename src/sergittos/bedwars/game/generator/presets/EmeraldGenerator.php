<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\generator\presets;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\generator\GeneratorType;

class EmeraldGenerator extends TextGenerator {

    public function getType(): GeneratorType {
        return GeneratorType::EMERALD;
    }

    public function getInitialSpeed(): int {
        return 60;
    }

    protected function getItem(): Item {
        return VanillaItems::EMERALD();
    }

}