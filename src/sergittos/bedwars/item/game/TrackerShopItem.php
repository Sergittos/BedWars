<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\game;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\form\shop\ShopForm;
use sergittos\bedwars\game\shop\Shop;
use sergittos\bedwars\game\shop\ShopFactory;
use sergittos\bedwars\item\BedwarsItem;
use sergittos\bedwars\session\Session;

class TrackerShopItem extends BedwarsItem {

    public function __construct() {
        parent::__construct("Tracker Shop", false);
    }

    public function onInteract(Session $session): void {
        $session->getPlayer()->sendForm(new ShopForm($session, "Tracker & Communication", ShopFactory::getShop(Shop::TRACKER), false));
    }

    protected function realItem(): Item {
        return VanillaItems::COMPASS();
    }

}