<?php

declare(strict_types=1);


namespace sergittos\bedwars\game\team;


use pocketmine\block\utils\DyeColor;
use pocketmine\math\Vector3;
use sergittos\bedwars\utils\ColorUtils;

trait TeamProperties {

    private string $name;
    private string $color;

    private Vector3 $spawn_point;
    private Vector3 $bed_position;

    private Area $zone;
    private Area $claim;

    public function getName(): string {
        return $this->name;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getDyeColor(): DyeColor {
        return ColorUtils::getDye($this->color);
    }

    public function getSpawnPoint(): Vector3 {
        return $this->spawn_point;
    }

    public function getBedPosition(): Vector3 {
        return $this->bed_position;
    }

    public function getZone(): Area {
        return $this->zone;
    }

    public function getClaim(): Area {
        return $this->claim;
    }

}