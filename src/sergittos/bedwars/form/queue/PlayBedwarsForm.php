<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\queue;


use EasyUI\element\Button;
use EasyUI\variant\SimpleForm;
use pocketmine\player\Player;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\form\queue\element\PlayGameButton;
use sergittos\bedwars\utils\GameUtils;

class PlayBedwarsForm extends SimpleForm {

    private int $players_per_team;

    public function __construct(int $players_per_team) {
        $this->players_per_team = $players_per_team;
        parent::__construct("Play bedwars " . GameUtils::getMode($players_per_team));
    }

    protected function onCreation(): void {
        $this->addButton(new PlayGameButton("Random map", BedWars::getInstance()->getGameManager()->findRandomGame($this->players_per_team)));
        $this->addSelectMapButton();
    }

    private function addSelectMapButton(): void {
        $button = new Button("Select a map");
        $button->setIcon(new ButtonIcon("https://cdn-icons-png.flaticon.com/512/5591/5591302.png"));
        $button->setSubmitListener(function(Player $player) {
            $player->sendForm(new SelectMapForm($this->players_per_team));
        });
        $this->addButton($button);
    }

}
