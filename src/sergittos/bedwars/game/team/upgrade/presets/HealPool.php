<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\presets;


use sergittos\bedwars\game\team\upgrade\Upgrade;
use sergittos\bedwars\game\team\upgrade\UpgradeIds;

class HealPool extends Upgrade {

    public function getId(): string {
        return UpgradeIds::HEAL_POOL;
    }

    public function getName(): string {
        return "Heal Pool";
    }

    public function getLevels(): int {
        return 1;
    }

    public function getPrice(): int {
        return 1;
    }

}