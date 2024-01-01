<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use EasyUI\element\Dropdown;
use EasyUI\element\Option;
use EasyUI\utils\FormResponse;
use pocketmine\player\Player;
use sergittos\bedwars\form\CustomForm;
use sergittos\bedwars\game\map\Map;
use sergittos\bedwars\game\map\MapFactory;
use sergittos\bedwars\utils\GameUtils;
use function array_map;

class RemoveModeForm extends CustomForm {

    private Map $map;

    public function __construct(Map $map) {
        $this->map = $map;
        parent::__construct("Remove mode");
    }

    protected function onCreation(): void {
        $modes = array_map(function(Map $map) {
            return $map->getPlayersPerTeam();
        }, MapFactory::getMapsByName($this->map->getName()));

        $dropdown = new Dropdown("Select the mode:");
        foreach($modes as $mode) {
            $dropdown->addOption(new Option((string) $mode, GameUtils::getMode($mode)));
        }
        $this->addElement("players_per_team", $dropdown);
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $players_per_team = (int) $response->getDropdownSubmittedOptionId("players_per_team");

    }

}