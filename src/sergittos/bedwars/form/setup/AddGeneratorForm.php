<?php

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
        $dropdown->addOption(new Option(GeneratorType::DIAMOND->name, "Diamond Generator"));
        $dropdown->addOption(new Option(GeneratorType::EMERALD->name, "Emerald Generator"));

        $this->addElement("id", $dropdown);
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $this->session->getMapSetup()->setStep(new AddGeneratorStep(
            GeneratorType::fromString($response->getDropdownSubmittedOptionId("id"))
        ));
    }

}