<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\team\upgrade\presets;


use sergittos\bedwars\game\team\upgrade\Upgrade;

class HealPool extends Upgrade {

    public function getName(): string {
        return "Heal Pool";
    }

    public function getLevels(): int {
        return 1;
    }

}