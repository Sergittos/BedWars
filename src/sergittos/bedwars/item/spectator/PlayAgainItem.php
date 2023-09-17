<?php

declare(strict_types=1);


namespace sergittos\bedwars\item\spectator;


use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use sergittos\bedwars\form\queue\PlayBedwarsForm;
use sergittos\bedwars\session\Session;

class PlayAgainItem extends SpectatorItem {

    public function __construct() {
        parent::__construct("{GREEN}Play again");
    }

    protected function onSpectatorInteract(Session $session): void {
        $form = new PlayBedwarsForm($session->getGame()->getMap()->getPlayersPerTeam());
        $form->setTitle("Play again?");

        $session->getPlayer()->sendForm($form);
    }

    protected function realItem(): Item {
        return VanillaItems::PAPER();
    }

}