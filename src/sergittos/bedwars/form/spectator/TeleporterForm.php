<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\form\spectator;


use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\SessionFactory;

class TeleporterForm extends SimpleForm {

    private Session $session;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Teleporter");
    }

    protected function onCreation(): void {
        foreach($this->session->getGame()->getPlayers() as $target) {
            if(!$target->isRespawning()) {
                $this->addTeleportButton($target);
            }
        }
    }

    private function addTeleportButton(Session $target): void {
        $button = new Button($target->getUsername());
        $button->setSubmitListener(function(Player $player) use ($target) {
            $session = SessionFactory::getSession($player);
            if(!$session->isSpectator()) {
                $session->message("{RED}You can't do this!");
                return;
            }
            if(!$target->isPlaying()) {
                $session->message("{RED}The player you want to teleport is no longer playing!");
                return;
            }
            if($target->isRespawning()) {
                $session->message("{RED}The player you want to teleport is dead!");
                return;
            }

            $session->setTrackingSession(SessionFactory::getSession($player = $target->getPlayer()));
            $session->getPlayer()->teleport($player->getPosition());
            $session->message("{GREEN}You teleported to " . $target->getUsername() . " successfully!");
        });
        $this->addButton($button);
    }

}