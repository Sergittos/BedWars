<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use EasyUI\element\ModalOption;
use EasyUI\variant\ModalForm;
use pocketmine\player\Player;
use sergittos\bedwars\game\generator\Generator;
use sergittos\bedwars\session\SessionFactory;

class RemoveGeneratorForm extends ModalForm {

    private Generator $generator;

    public function __construct(Generator $generator) {
        $this->generator = $generator;
        parent::__construct(
            "Remove " . $generator->getName() . " Generator",
            "Are you sure you want to delete this generator?",
            new ModalOption("Yes!"), new ModalOption("No!")
        );
    }

    protected function onAccept(Player $player): void {
        $session = SessionFactory::getSession($player);
        if($session->isCreatingMap()) {
            $session->getMapSetup()->getMapBuilder()->removeGenerator($this->generator);
            $session->playSound("random.orb");
            $session->message("{GREEN}You have removed the generator successfully!");
        }
    }

    protected function onDeny(Player $player): void {
        $session = SessionFactory::getSession($player);
        if($session->isCreatingMap()) {
            $player->sendForm(new RemoveGeneratorsForm($session));
        }

    }

}