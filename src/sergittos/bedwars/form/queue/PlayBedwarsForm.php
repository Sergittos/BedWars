<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\form\queue;


use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\form\queue\element\PlayGameButton;
use sergittos\bedwars\utils\GameUtils;

class PlayBedwarsForm extends SimpleForm {

    private int $playersPerTeam;

    public function __construct(int $playersPerTeam) {
        $this->playersPerTeam = $playersPerTeam;
        parent::__construct("Play bedwars " . GameUtils::getMode($playersPerTeam));
    }

    protected function onCreation(): void {
        $this->addButton(new PlayGameButton("Random map", null, $this->playersPerTeam));
        $this->addSelectMapButton();
    }

    private function addSelectMapButton(): void {
        $button = new Button("Select a map");
        $button->setSubmitListener(function(Player $player) {
            $player->sendForm(new SelectMapForm($this->playersPerTeam));
        });
        $this->addButton($button);
    }

}