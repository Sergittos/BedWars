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

    protected function checkSlots(Player $player, int $players_per_team, $max_capacity): bool {
        if((!is_numeric($max_capacity) and $max_capacity <= 1) or $players_per_team !== 1 and $max_capacity % 2 !== 0) {
            $player->sendMessage(TextFormat::RED . "You must set a valid number of slots!");
            return false;
        } elseif($max_capacity / $players_per_team > 8) {
            $player->sendMessage(TextFormat::RED . "Your map cannot have more than 8 teams!");
            return false;
        }
        return true;
    }

}