<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\entity\shop;



use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\Form;
use sergittos\bedwars\form\shop\ShopForm;
use sergittos\bedwars\game\shop\Shop;
use sergittos\bedwars\game\shop\ShopFactory;
use sergittos\bedwars\session\Session;

class ItemShopVillager extends Villager {

    protected function getName(): string {
        return "ITEM SHOP";
    }

    protected function getForm(Session $session): Form {
        return new ShopForm($session, "Quick buy", ShopFactory::getShop(Shop::ITEM));
    }

}