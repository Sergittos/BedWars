<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

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