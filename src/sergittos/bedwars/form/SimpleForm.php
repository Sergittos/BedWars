<?php

declare(strict_types=1);


namespace sergittos\bedwars\form;


use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\element\Button;
use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\Form;
use sergittos\bedwars\libs\_8164d5e56d495a6e\dresnite\EasyUI\variant\SimpleForm as EasyUISimpleForm;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class SimpleForm extends EasyUISimpleForm {

    public function addRedirectFormButton(string $name, Form $form): void {
        $button = new Button($name);
        $button->setSubmitListener(function(Player $player) use ($form) {
            $player->sendForm($form);
        });
        $this->addButton($button);
    }

    protected function vectorToString(Vector3 $vector): string { // this function shouldn't be there
        return "X: " . $vector->getFloorX() . ", Y: " . $vector->getFloorY() . ", Z: " . $vector->getFloorZ();
    }

}