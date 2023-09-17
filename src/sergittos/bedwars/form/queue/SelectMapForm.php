<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\queue;


use EasyUI\variant\SimpleForm;
use sergittos\bedwars\BedWars;
use sergittos\bedwars\form\queue\element\PlayGameButton;
use sergittos\bedwars\game\map\MapFactory;

class SelectMapForm extends SimpleForm {

    private int $players_per_team;

    public function __construct(int $players_per_team) {
        $this->players_per_team = $players_per_team;
        parent::__construct("Select a map!");
    }

    protected function onCreation(): void {
        foreach(MapFactory::getMapsByPlayers($this->players_per_team) as $map) {
            $this->addButton(new PlayGameButton($map->getName(), BedWars::getInstance()->getGameManager()->findGame($map)));
        }
    }

}