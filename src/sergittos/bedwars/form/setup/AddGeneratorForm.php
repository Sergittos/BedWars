<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\form\setup;


use EasyUI\element\Dropdown;
use EasyUI\element\Option;
use EasyUI\utils\FormResponse;
use pocketmine\player\Player;
use sergittos\bedwars\form\CustomForm;
use sergittos\bedwars\game\generator\GeneratorType;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\session\setup\step\AddGeneratorStep;

class AddGeneratorForm extends CustomForm {

    private Session $session;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Add generator");
    }

    protected function onCreation(): void {
        $dropdown = new Dropdown("Select the generator:");
        $dropdown->addOption(new Option(GeneratorType::DIAMOND->value, "Diamond Generator"));
        $dropdown->addOption(new Option(GeneratorType::EMERALD->value, "Emerald Generator"));

        $this->addElement("id", $dropdown);
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $this->session->getMapSetup()->setStep(new AddGeneratorStep(
            GeneratorType::from($response->getDropdownSubmittedOptionId("id"))
        ));
    }

}