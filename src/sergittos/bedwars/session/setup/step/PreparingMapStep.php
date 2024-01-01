<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\setup\step;


class PreparingMapStep extends Step { // This is like the default step

    protected function onStart(): void {
        $this->session->giveCreatingMapItems();
    }

}