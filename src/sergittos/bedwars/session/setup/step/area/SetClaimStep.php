<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\step\area;


use sergittos\bedwars\game\team\Area;

class SetClaimStep extends SetAreaStep {

    protected function setArea(Area $area): void {
        $this->team->setClaim($area);
    }

}