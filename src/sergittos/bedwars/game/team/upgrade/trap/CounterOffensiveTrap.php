<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\trap;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;

class CounterOffensiveTrap extends Trap {

    public function __construct() {
        parent::__construct("Counter-Offensive Trap");
    }

    public function trigger(Session $session, Team $team): void {
        foreach($team->getMembers() as $member) {
            $member->addEffect(new EffectInstance(VanillaEffects::SPEED(), 20 * 15, 1, false));
            $member->addEffect(new EffectInstance(VanillaEffects::JUMP_BOOST(), 20 * 15, 1, false));
        }
    }

}