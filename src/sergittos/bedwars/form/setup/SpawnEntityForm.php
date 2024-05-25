<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use EasyUI\utils\FormResponse;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\entity\PlayBedwarsEntity;
use sergittos\bedwars\form\CustomForm;

class SpawnEntityForm extends CustomForm {

    public function __construct() {
        parent::__construct("Spawn entity");
    }

    protected function onCreation(): void {
        $this->addSelectModeDropdown();
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $playersPerTeam = (int) $response->getDropdownSubmittedOptionId("players_per_team");

        $entity = new PlayBedwarsEntity($player->getLocation(), $player->getSkin(), CompoundTag::create()->setInt("players_per_team", $playersPerTeam));
        $entity->spawnToAll();

        $player->sendMessage(TextFormat::GREEN . "You have spawned the entity successfully!");
    }

}