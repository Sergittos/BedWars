<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\upgrades\product;


use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\shop\upgrades\UpgradesProduct;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\game\team\upgrade\Upgrade;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\GameUtils;

class UpgradeProduct extends UpgradesProduct {

    private Upgrade $upgrade;

    public function __construct(Upgrade $upgrade) {
        $this->upgrade = $upgrade;
        parent::__construct($upgrade->getName(), $upgrade->getPrice());
    }

    public function canBePurchased(Session $session): bool {
        return $this->getUpgrade($session->getTeam())->canLevelUp();
    }

    protected function purchase(Team $team): void {
        $this->getUpgrade($team)->levelUp($team);
    }

    public function getDisplayName(Session $session): string {
        $upgrade = $this->getUpgrade($session->getTeam());
        $level = $upgrade->getLevel();
        $levels = $upgrade->getLevels();

        $name = parent::getDisplayName($session);
        if($levels > 1 and $level !== $levels) {
            $name .= " " . GameUtils::intToRoman($level + 1);
        }
        return $name;
    }

    public function getDescription(Session $session): string {
        if(!$this->getUpgrade($session->getTeam())->canLevelUp()) {
            return TextFormat::RED . "You already have this upgrade!";
        }
        return parent::getDescription($session);
    }

    private function getUpgrade(Team $team): Upgrade {
        return $team->getUpgrades()->get($this->upgrade->getId());
    }

}