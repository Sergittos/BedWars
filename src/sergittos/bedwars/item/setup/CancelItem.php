<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\setup;


use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\setup\step\PreparingMapStep;

class CancelItem extends SetupItem {

    public function __construct() {
        parent::__construct("{RED}Cancel");
    }

    public function onInteract(Session $session): void {
        if($session->isCreatingMap()) {
            $session->getMapSetup()->setStep(new PreparingMapStep());
        }
    }

    protected function realItem(): Item {
        return VanillaBlocks::BED()->setColor(DyeColor::RED)->asItem();
    }

}