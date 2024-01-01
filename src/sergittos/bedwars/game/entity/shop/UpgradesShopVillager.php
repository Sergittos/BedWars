<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\entity\shop;


use EasyUI\Form;
use sergittos\bedwars\form\shop\ShopForm;
use sergittos\bedwars\game\shop\Shop;
use sergittos\bedwars\game\shop\ShopFactory;
use sergittos\bedwars\session\Session;

class UpgradesShopVillager extends Villager {

    protected function getName(): string {
        return "TEAM\n{AQUA}UPGRADES";
    }

    protected function getForm(Session $session): Form {
        return new ShopForm($session, "Upgrades & Traps", ShopFactory::getShop(Shop::UPGRADES), false);
    }

}