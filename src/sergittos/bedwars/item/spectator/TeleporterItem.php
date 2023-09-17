<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\spectator;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\form\spectator\TeleporterForm;
use sergittos\bedwars\session\Session;

class TeleporterItem extends SpectatorItem {

    public function __construct() {
        parent::__construct("{GREEN}Teleporter");
    }

    protected function onSpectatorInteract(Session $session): void {
        $session->getPlayer()->sendForm(new TeleporterForm($session));
    }

    protected function realItem(): Item {
        return VanillaItems::COMPASS();
    }

}