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
        $flyingSpeed = (int) $response->getSliderSubmittedStep("flying_speed");
        $autoTeleport = $response->getToggleSubmittedChoice("auto_teleport");
        $nightVision = $response->getToggleSubmittedChoice("night_vision");

        $settings = $this->session->getSpectatorSettings();
        if($settings->getFlyingSpeed() !== $flyingSpeed) {
            $settings->setFlyingSpeed($flyingSpeed);
        }
        if($settings->getAutoTeleport() !== $autoTeleport) {
            $settings->setAutoTeleport($autoTeleport);
        }
        if($settings->getNightVision() !== $nightVision) {
            $settings->setNightVision($nightVision);
        }
        if($this->session->isSpectator()) {
            $settings->apply();
        }
    }

}