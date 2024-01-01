<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use EasyUI\element\ModalOption;
use EasyUI\variant\ModalForm;
use pocketmine\player\Player;
use sergittos\bedwars\game\map\Map;

class RemoveMapForm extends ModalForm {

    private Map $map;

    public function __construct(Map $map) {
        $this->map = $map;
        parent::__construct(
            "Remove " . $map->getName() . " map", "Are you sure you want to delete this map?",
            new ModalOption("Yes!"), new ModalOption("No!")
        );
    }

    protected function onAccept(Player $player): void {

    }

    protected function onDeny(Player $player): void {

    }

}