<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\spectator;


use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use sergittos\bedwars\session\Session;

class ReturnToLobbyItem extends SpectatorItem {

    public function __construct() {
        parent::__construct("{RED}Return to lobby");
    }

    protected function onSpectatorInteract(Session $session): void {
        $session->getGame()->removeSpectator($session);
    }

    protected function realItem(): Item {
        return VanillaBlocks::BED()->setColor(DyeColor::RED())->asItem();
    }

}