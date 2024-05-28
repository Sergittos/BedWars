<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\item\spectator;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\form\queue\PlayBedwarsForm;
use sergittos\bedwars\session\Session;

class PlayAgainItem extends SpectatorItem {

    public function __construct() {
        parent::__construct("{GREEN}Play again");
    }

    protected function onSpectatorInteract(Session $session): void {
        $form = new PlayBedwarsForm($session->getGame()->getMap()->getMode());
        $form->setTitle("Play again?");

        $session->getPlayer()->sendForm($form);
    }

    protected function getMaterial(): Item {
        return VanillaItems::PAPER();
    }

}