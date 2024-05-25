<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\entity\shop;


use EasyUI\Form;
use sergittos\bedwars\form\shop\CategoryForm;
use sergittos\bedwars\form\shop\ShopForm;
use sergittos\bedwars\game\shop\Shop;
use sergittos\bedwars\game\shop\ShopFactory;
use sergittos\bedwars\game\shop\upgrades\UpgradesShop;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\ColorUtils;

class UpgradesShopVillager extends Villager {

    protected function getName(): string {
        return "TEAM\n{AQUA}UPGRADES";
    }

    protected function getForm(Session $session): Form {
        /** @var UpgradesShop $shop */
        $shop = ShopFactory::getShop(Shop::UPGRADES);

        $form = new CategoryForm($session, $shop->getCategories()[0]);
        $form->addRedirectFormButton(
            ColorUtils::translate("{GOLD}{BOLD}Traps{RESET}\n{YELLOW}Click to view!"),
            new CategoryForm($session, $shop->getCategories()[1])
        );
        return $form;
    }

}