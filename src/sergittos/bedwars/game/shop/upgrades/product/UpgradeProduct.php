<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\upgrades\product;


use sergittos\bedwars\game\shop\upgrades\UpgradesProduct;
use sergittos\bedwars\game\team\Team;

class UpgradeProduct extends UpgradesProduct {

    protected function canPurchase(Team $team): bool {
        $upgrade = $team->getUpgrades()->getUpgrade($this->name);
        if($upgrade->canLevelUp()) {
            $upgrade->levelUp($team);
            return true;
        }
        return false;
    }

}