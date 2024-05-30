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
use sergittos\bedwars\form\shop\ShopForm;
use sergittos\bedwars\game\shop\ShopRegistry;
use sergittos\bedwars\session\Session;

class ItemShopVillager extends Villager {

    protected function getName(): string {
        return "ITEM SHOP";
    }

    protected function getForm(Session $session): Form {
        return new ShopForm($session, "Quick buy", ShopRegistry::ITEM());
    }

}