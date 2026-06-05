<?php

declare(strict_types=1);


namespace sergittos\bedwars\form;


use dresnite\EasyUI\element\Dropdown;
use dresnite\EasyUI\element\Option;
use dresnite\EasyUI\variant\CustomForm as EasyUICustomForm;
use sergittos\bedwars\utils\GameUtils;

class CustomForm extends EasyUICustomForm {

    protected function addSelectModeDropdown(): void {
        $dropdown = new Dropdown("Select the mode:");
        foreach([1, 2, 4] as $players_per_team) {
            $dropdown->addOption(new Option((string) $players_per_team, GameUtils::getMode($players_per_team)));
        }
        $this->addElement("players_per_team", $dropdown);
    }

}