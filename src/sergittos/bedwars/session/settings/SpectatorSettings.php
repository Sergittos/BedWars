<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);


namespace sergittos\bedwars\session\settings;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\utils\Limits;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\GameUtils;

class SpectatorSettings {

    private Session $session;

    private int $flyingSpeed;

    private bool $autoTeleport;
    private bool $nightVision;

    public function __construct(Session $session, int $flyingSpeed, bool $autoTeleport, bool $nightVision) {
        $this->session = $session;
        $this->flyingSpeed = $flyingSpeed;
        $this->autoTeleport = $autoTeleport;
        $this->nightVision = $nightVision;
    }

    static public function fromData(Session $session, array $data): SpectatorSettings {
        return new SpectatorSettings($session, (int) $data["flying_speed"], (bool) $data["auto_teleport"], (bool) $data["night_vision"]);
    }

    public function getFlyingSpeed(): int {
        return $this->flyingSpeed;
    }

    public function getAutoTeleport(): bool {
        return $this->autoTeleport;
    }

    public function getNightVision(): bool {
        return $this->nightVision;
    }

    public function setFlyingSpeed(int $flyingSpeed): void {
        $this->flyingSpeed = $flyingSpeed;

        if($this->flyingSpeed !== 0) {
            $this->session->message("{GREEN}You now have Speed " . GameUtils::intToRoman($flyingSpeed) . "!");
        } else {
            $this->session->message("{RED}You no longer have any speed effects!");
        }
    }

    public function setAutoTeleport(bool $autoTeleport): void {
        $this->autoTeleport = $autoTeleport;

        if($this->autoTeleport) {
            $this->session->message("{GREEN}Once you select a player using your compass, it will auto teleport you to them!");
        } else {
            $this->session->message("{RED}You will no longer auto teleport to targets!");
        }
    }

    public function setNightVision(bool $nightVision): void {
        $this->nightVision = $nightVision;

        if($this->nightVision) {
            $this->session->message("{GREEN}You now have night vision!");
        } else {
            $this->session->message("{RED}You no longer have night vision!");
        }
    }

    public function apply(): void {
        $this->session->getPlayer()->getEffects()->clear();
        if($this->flyingSpeed !== 0) {
            $this->session->addEffect(new EffectInstance(VanillaEffects::SPEED(), Limits::INT32_MAX, $this->flyingSpeed - 1, false));
        }
        if($this->nightVision) {
            $this->session->addEffect(new EffectInstance(VanillaEffects::NIGHT_VISION(), Limits::INT32_MAX, 0, false));
        }
    }

}