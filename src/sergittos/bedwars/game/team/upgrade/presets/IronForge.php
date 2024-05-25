<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\presets;


use sergittos\bedwars\game\generator\presets\TeamEmeraldGenerator;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\game\team\upgrade\Upgrade;
use function array_rand;

class IronForge extends Upgrade {

    public function getName(): string {
        return "Iron Forge";
    }

    public function getLevels(): int {
        return 4;
    }

    protected function internalLevelUp(Team $team): void {
        $generators = $team->getGenerators();

        if($this->level === 3) { // generate emeralds
            $team->addGenerator(new TeamEmeraldGenerator($generators[array_rand($generators)]->getPosition()));
            return;
        }

        foreach($generators as $generator) {
            $speed = 1 / $generator->getInitialSpeed();
            $generator->setCountdown($this->level / 2 * $speed + $speed);
        }
    }

}