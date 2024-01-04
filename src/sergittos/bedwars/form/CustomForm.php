<?php

declare(strict_types=1);


namespace sergittos\bedwars\form;


use EasyUI\element\Dropdown;
use EasyUI\element\Option;
use EasyUI\variant\CustomForm as EasyUICustomForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\utils\GameUtils;
use function is_numeric;

class CustomForm extends EasyUICustomForm {

    protected function addSelectModeDropdown(): void {
        $dropdown = new Dropdown("Select the mode:");
        foreach([1, 2, 4] as $players_per_team) {
            $dropdown->addOption(new Option((string) $players_per_team, GameUtils::getMode($players_per_team)));
        }
        $this->addElement("players_per_team", $dropdown);
    }

}