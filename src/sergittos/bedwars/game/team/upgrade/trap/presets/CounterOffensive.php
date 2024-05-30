<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\trap\presets;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\game\team\upgrade\trap\Trap;
use sergittos\bedwars\session\Session;

class CounterOffensive implements Trap {

    public function getName(): string {
        return "Counter-Offensive Trap";
    }

    public function trigger(Session $session, Team $team): void {
        foreach($team->getMembers() as $member) {
            $member->addEffect(new EffectInstance(VanillaEffects::SPEED(), 20 * 15, 1, false));
            $member->addEffect(new EffectInstance(VanillaEffects::JUMP_BOOST(), 20 * 15, 1, false));
        }
    }

}