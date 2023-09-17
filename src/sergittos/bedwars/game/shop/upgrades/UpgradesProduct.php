<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\upgrades;


use pocketmine\item\VanillaItems;
use sergittos\bedwars\game\shop\Product;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;

abstract class UpgradesProduct extends Product {

    public function __construct(string $name, int $price) {
        parent::__construct($name, $name, $price, VanillaItems::DIAMOND());
    }

    public function onPurchase(Session $session): bool {
        if(!$session->hasTeam()) {
            return false;
        }
        $team = $session->getTeam();

        if(!$this->canPurchase($team)) {
            $session->message("{RED}You already have this upgrade!");
            return false;
        }
        return true;
    }

    abstract protected function canPurchase(Team $team): bool;

}