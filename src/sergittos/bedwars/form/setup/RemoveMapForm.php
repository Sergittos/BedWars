<?php

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use EasyUI\element\ModalOption;
use EasyUI\variant\ModalForm;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use sergittos\bedwars\game\map\Map;
use sergittos\bedwars\game\map\task\DeleteMapTask;

class RemoveMapForm extends ModalForm {

    private Map $map;

    public function __construct(Map $map) {
        $this->map = $map;
        parent::__construct(
            "Remove " . $map->getName() . " map", "Are you sure you want to delete this map?",
            new ModalOption("Yes"), new ModalOption("No")
        );
    }

    protected function onAccept(Player $player): void {
        Server::getInstance()->getAsyncPool()->submitTask(new DeleteMapTask($this->map));

        $player->sendMessage(TextFormat::GREEN . "You've deleted the map " . $this->map->getName() . " successfully!");
    }

}