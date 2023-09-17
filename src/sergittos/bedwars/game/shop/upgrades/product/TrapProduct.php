<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\shop\upgrades\product;


use sergittos\bedwars\game\shop\upgrades\UpgradesProduct;
use sergittos\bedwars\game\team\Team;
use sergittos\bedwars\game\team\upgrade\trap\AlarmTrap;
use sergittos\bedwars\game\team\upgrade\trap\CounterOffensiveTrap;
use sergittos\bedwars\game\team\upgrade\trap\DefaultTrap;
use sergittos\bedwars\game\team\upgrade\trap\MinerFatigueTrap;
use sergittos\bedwars\game\team\upgrade\trap\Trap;

class TrapProduct extends UpgradesProduct {

    protected function canPurchase(Team $team): bool {
        $upgrades = $team->getUpgrades();
        if(!$upgrades->hasTrap($this->name)) {
            $upgrades->addTrap($this->getTrap());
            return true;
        }
        return false;
    }

    private function getTrap(): Trap {
        return match($this->name) {
            "It's a trap" => new DefaultTrap(),
            "Counter-Offensive Trap" => new CounterOffensiveTrap(),
            "Alarm Trap" => new AlarmTrap(),
            "Miner Fatigue Trap" => new MinerFatigueTrap()
        };
    }

}