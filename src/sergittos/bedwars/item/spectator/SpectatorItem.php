<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
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