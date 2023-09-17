<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\trap;


use pocketmine\entity\effect\VanillaEffects;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;

class AlarmTrap extends Trap {

    public function __construct() {
        parent::__construct("Alarm Trap");
    }

    public function trigger(Session $session, Team $team): void {
        $session->getPlayer()->getEffects()->remove(VanillaEffects::INVISIBILITY());
    }

}