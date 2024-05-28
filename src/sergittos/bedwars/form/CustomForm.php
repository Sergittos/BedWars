<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\form;


use EasyUI\element\Dropdown;
use EasyUI\element\Option;
use EasyUI\variant\CustomForm as EasyUICustomForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\map\Mode;
use sergittos\bedwars\utils\GameUtils;
use function is_numeric;

class CustomForm extends EasyUICustomForm {

    protected function addSelectModeDropdown(): void {
        $dropdown = new Dropdown("Select the mode:");
        foreach(Mode::cases() as $mode) {
            $dropdown->addOption(new Option((string) $mode->value, $mode->getDisplayName()));
        }
        $this->addElement("players_per_team", $dropdown);
    }

}