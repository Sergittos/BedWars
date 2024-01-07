<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\generator\presets;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\game\generator\GeneratorType;

class TeamEmeraldGenerator extends Generator {

    public function getType(): GeneratorType {
        return GeneratorType::TEAM_EMERALD;
    }

    public function getInitialSpeed(): int {
        return 30;
    }

    protected function getItem(): Item {
        return VanillaItems::EMERALD();
    }

}