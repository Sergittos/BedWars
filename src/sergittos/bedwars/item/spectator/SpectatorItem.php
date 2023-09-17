<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\item\spectator;


use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\session\Session;

abstract class SpectatorItem extends BedwarsItem {

    public function onInteract(Session $session): void {
        if($session->isSpectator()) {
            $this->onSpectatorInteract($session);
        }
    }

    abstract protected function onSpectatorInteract(Session $session): void;

}