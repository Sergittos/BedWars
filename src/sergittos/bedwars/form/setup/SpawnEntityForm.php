<?php

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
        $players_per_team = (int) $response->getDropdownSubmittedOptionId("players_per_team");

        $entity = new PlayBedwarsEntity($player->getLocation(), $player->getSkin(), CompoundTag::create()->setInt("players_per_team", $players_per_team));
        $entity->spawnToAll();

        $player->sendMessage(TextFormat::GREEN . "You have spawned the entity successfully!");
    }

}