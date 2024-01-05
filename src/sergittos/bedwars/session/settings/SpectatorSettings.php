<?php

declare(strict_types=1);


namespace sergittos\bedwars\session\settings;


use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\utils\Limits;
use sergittos\bedwars\session\Session;
use sergittos\bedwars\utils\GameUtils;

class SpectatorSettings {

    private Session $session;

    private int $flying_speed;

    private bool $auto_teleport;
    private bool $night_vision;

    public function __construct(Session $session, int $flying_speed, bool $auto_teleport, bool $night_vision) {
        $this->session = $session;
        $this->flying_speed = $flying_speed;
        $this->auto_teleport = $auto_teleport;
        $this->night_vision = $night_vision;
    }

    static public function fromData(Session $session, array $data): SpectatorSettings {
        return new SpectatorSettings($session, (int) $data["flying_speed"], (bool) $data["auto_teleport"], (bool) $data["night_vision"]);
    }

    public function getFlyingSpeed(): int {
        return $this->flying_speed;
    }

    public function getAutoTeleport(): bool {
        return $this->auto_teleport;
    }

    public function getNightVision(): bool {
        return $this->night_vision;
    }

    public function setFlyingSpeed(int $flying_speed): void {
        $this->flying_speed = $flying_speed;

        if($this->flying_speed !== 0) {
            $this->session->message("{GREEN}You now have Speed " . GameUtils::intToRoman($flying_speed) . "!");
        } else {
            $this->session->message("{RED}You no longer have any speed effects!");
        }
    }

    public function setAutoTeleport(bool $auto_teleport): void {
        $this->auto_teleport = $auto_teleport;

        if($this->auto_teleport) {
            $this->session->message("{GREEN}Once you select a player using your compass, it will auto teleport you to them!");
        } else {
            $this->session->message("{RED}You will no longer auto teleport to targets!");
        }
    }

    public function setNightVision(bool $night_vision): void {
        $this->night_vision = $night_vision;

        if($this->night_vision) {
            $this->session->message("{GREEN}You now have night vision!");
        } else {
            $this->session->message("{RED}You no longer have night vision!");
        }
    }

    public function apply(): void {
        $this->session->getPlayer()->getEffects()->clear();
        if($this->flying_speed !== 0) {
            $this->session->addEffect(new EffectInstance(VanillaEffects::SPEED(), Limits::INT32_MAX, $this->flying_speed - 1, false));
        }
        if($this->night_vision) {
            $this->session->addEffect(new EffectInstance(VanillaEffects::NIGHT_VISION(), Limits::INT32_MAX, 0, false));
        }
    }

}