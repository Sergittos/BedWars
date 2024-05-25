<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\presets;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\utils\Limits;
use sergittos\bedwars\game\team\upgrade\Upgrade;
use sergittos\bedwars\session\Session;

class ManiacMiner extends Upgrade {

    public function getName(): string {
        return "Maniac Miner";
    }

    public function getLevels(): int {
        return 2;
    }

    public function internalApplySession(Session $session): void {
        $session->addEffect(new EffectInstance(VanillaEffects::HASTE(), Limits::INT32_MAX, $this->level - 1, false));
    }

}