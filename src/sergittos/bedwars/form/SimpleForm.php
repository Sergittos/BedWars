<?php

declare(strict_types=1);


namespace sergittos\bedwars\form;


use EasyUI\element\Button;
use EasyUI\Form;
use EasyUI\variant\SimpleForm as EasyUISimpleForm;
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