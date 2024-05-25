<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
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