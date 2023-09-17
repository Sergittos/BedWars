<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\presets;


use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\generator\Generator;
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
            $team->addGenerator(new Generator(
                Generator::EMERALD, "Emerald", 30, $generators[array_rand($generators)]->getPosition(), VanillaItems::EMERALD(), false
            ));
            return;
        }

        foreach($generators as $generator) {
            $speed = $generator->getInitialSpeed();
            $generator->setSpeed($this->level / 2 * $speed + $speed);
        }
    }

}