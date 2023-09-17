<?php
/*
* Copyright (C) Sergittos - All Rights Reserved
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
*/

declare(strict_types=1);


namespace sergittos\bedwars\form\spectator;


use EasyUI\element\Label;
use EasyUI\element\Slider;
use EasyUI\element\Toggle;
use EasyUI\utils\FormResponse;
use EasyUI\variant\CustomForm;
use pocketmine\player\Player;
use sergittos\bedwars\session\Session;

class SpectatorSettingsForm extends CustomForm {

    private Session $session;

    public function __construct(Session $session) {
        $this->session = $session;
        parent::__construct("Spectator settings");
    }

    protected function onCreation(): void {
        $settings = $this->session->getSpectatorSettings();

        $this->addElement("label", new Label("Change the spectator settings in this window."));
        $this->addElement("flying_speed", new Slider("Set your flying speed", 0, 4, $settings->getFlyingSpeed(), 1));
        $this->addElement("auto_teleport", new Toggle("Auto Teleport", $settings->getAutoTeleport()));
        $this->addElement("night_vision", new Toggle("Night Vision", $settings->getNightVision()));
    }

    protected function onSubmit(Player $player, FormResponse $response): void {
        $flying_speed = (int) $response->getSliderSubmittedStep("flying_speed");
        $auto_teleport = $response->getToggleSubmittedChoice("auto_teleport");
        $night_vision = $response->getToggleSubmittedChoice("night_vision");

        $settings = $this->session->getSpectatorSettings();
        if($settings->getFlyingSpeed() !== $flying_speed) {
            $settings->setFlyingSpeed($flying_speed);
        }
        if($settings->getAutoTeleport() !== $auto_teleport) {
            $settings->setAutoTeleport($auto_teleport);
        }
        if($settings->getNightVision() !== $night_vision) {
            $settings->setNightVision($night_vision);
        }
        if($this->session->isSpectator()) {
            $settings->apply();
        }
    }

}