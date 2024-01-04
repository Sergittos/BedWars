<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\setup;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\form\setup\SetupMapForm;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\session\Session;

class ConfigurationItem extends BedwarsItem {

    public function __construct() {
        parent::__construct("Configuration");
    }

    public function onInteract(Session $session): void {
        $session->getPlayer()->sendForm(new SetupMapForm($session));
    }

    protected function realItem(): Item {
        return VanillaItems::PAPER();
    }

}