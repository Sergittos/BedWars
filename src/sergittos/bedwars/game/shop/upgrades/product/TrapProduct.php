<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\upgrades\product;


use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\shop\upgrades\UpgradesProduct;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\GameUtils;

class TrapProduct extends UpgradesProduct {

    protected function canPurchase(Team $team): bool {
        $upgrades = $team->getUpgrades();
        if(!$upgrades->hasTrap($this->name) and $upgrades->getTrapsCount() < 3) {
            $upgrades->addTrap(GameUtils::getTrapByName($this->name));
            return true;
        }
        return false;
    }

    public function getDescription(Session $session): string {
        $upgrades = $session->getTeam()->getUpgrades();
        if($upgrades->hasTrap($this->name)) {
            return TextFormat::RED . "You already have this trap!";
        }
        if($upgrades->getTrapsCount() >= 3) {
            return TextFormat::RED . "You cannot have more than 3 traps!";
        }
        return parent::getDescription($session);
    }

}