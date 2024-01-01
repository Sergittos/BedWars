<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use EasyUI\element\Input;
use EasyUI\utils\FormResponse;
use pocketmine\player\Player;
use sergittos\bedwars\form\CustomForm;
use sergittos\bedwars\game\map\Map;

class AddModeForm extends CustomForm {

    private Map $map;

    public function __construct(Map $map) {
        $this->map = $map;
        parent::__construct("Add mode");
    }

    protected function onCreation(): void {
        $this->addElement("max_capacity", new Input("Set the slots:"));
        $this->addSelectModeDropdown();
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $max_capacity = $response->getInputSubmittedText("max_capacity");
        $players_per_team = (int) $response->getDropdownSubmittedOptionId("players_per_team");

        if($this->checkSlots($player, $players_per_team, $max_capacity)) { // check more stuff
            $this->addMode($players_per_team, (int) $max_capacity);
        }
    }

    private function addMode(int $players_per_team, int $max_capacity): void {

    }

}