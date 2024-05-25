<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
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