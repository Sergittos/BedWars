<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\trap;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;

class DefaultTrap extends Trap {

    public function __construct() {
        parent::__construct("It's a trap!");
    }

    public function trigger(Session $session, Team $team): void {
        $session->addEffect(new EffectInstance(VanillaEffects::BLINDNESS(), 20 * 8, 0, false));
        $session->addEffect(new EffectInstance(VanillaEffects::SLOWNESS(), 20 * 8, 0, false));
    }

}