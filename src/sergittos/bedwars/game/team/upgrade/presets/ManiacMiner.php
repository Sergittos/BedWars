<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
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