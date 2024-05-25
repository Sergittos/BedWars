<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\generator\presets;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\generator\GeneratorType;

class GoldGenerator extends Generator {

    public function getType(): GeneratorType {
        return GeneratorType::GOLD;
    }

    public function getInitialSpeed(): int {
        return 5;
    }

    protected function getItem(): Item {
        return VanillaItems::GOLD_INGOT();
    }

}